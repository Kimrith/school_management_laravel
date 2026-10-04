<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Enums\TeacherStatus;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'all');

        $query = TeacherProfile::with(['user', 'taughtSubjects', 'taughtClassrooms']);

        if ($statusFilter !== 'all' && in_array($statusFilter, TeacherStatus::values(), true)) {
            $query->whereHas('user', function ($q) use ($statusFilter) {
                $q->where('status', $statusFilter);
            });
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->trim();
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                    ->orWhere('qualification', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $teachers = $query->latest()->paginate(7)->withQueryString();

        $counts = [
            'all' => TeacherProfile::count(),
            'active' => TeacherProfile::whereHas('user', fn ($q) => $q->where('status', TeacherStatus::Active->value))->count(),
            'inactive' => TeacherProfile::whereHas('user', fn ($q) => $q->where('status', TeacherStatus::Inactive->value))->count(),
            'suspended' => TeacherProfile::whereHas('user', fn ($q) => $q->where('status', TeacherStatus::Suspended->value))->count(),
        ];

        $subjects = Subject::orderBy('name')->get();
        if ($subjects->isEmpty()) {
            TeacherProfile::whereNotNull('specialization')->update(['specialization' => null]);
        }

        $classrooms = Classroom::orderBy('name')->get();

        return view('admin.teachers.index', compact('teachers', 'counts', 'statusFilter', 'subjects', 'classrooms'));
    }

    public function suspended(Request $request)
    {
        $query = TeacherProfile::with(['user', 'taughtSubjects', 'taughtClassrooms'])
            ->whereHas('user', function ($q) {
                $q->where('status', TeacherStatus::Suspended->value);
            });

        if ($request->filled('search')) {
            $search = $request->string('search')->trim();
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                    ->orWhere('qualification', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $teachers = $query->latest()->paginate(7)->withQueryString();
        $activeCount = TeacherProfile::whereHas('user', fn ($q) => $q->where('status', '!=', TeacherStatus::Suspended->value))->count();
        $suspendedCount = TeacherProfile::whereHas('user', fn ($q) => $q->where('status', TeacherStatus::Suspended->value))->count();

        return view('admin.teachers.suspended', compact('teachers', 'activeCount', 'suspendedCount'));
    }

    public function create()
    {
        return redirect()->route('admin.teachers.index', ['create' => 1]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:50',
            'qualification' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:500',
            'specializations' => 'nullable|array',
            'specializations.*' => 'string|max:255',
            'classrooms' => 'nullable|array',
            'classrooms.*' => 'exists:classrooms,id',
            'classroom_ids' => 'nullable|array',
            'classroom_ids.*' => 'exists:classrooms,id',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        $specialization = null;
        if (! empty($validated['specializations'])) {
            $specialization = implode(', ', array_filter($validated['specializations']));
        } elseif (! empty($validated['specialization'])) {
            $specialization = $validated['specialization'];
        }

        $selectedClassrooms = array_values(array_filter(array_map('intval', (array) ($request->input('classrooms') ?? $request->input('classroom_ids') ?? []))));

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars/teachers', 'public');
        }

        DB::transaction(function () use ($validated, $specialization, $selectedClassrooms, $avatarPath) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make('password123'),
                'role' => Role::Teacher,
                'status' => TeacherStatus::Active->value,
            ]);

            TeacherProfile::create([
                'user_id' => $user->id,
                'avatar' => $avatarPath,
                'phone' => $validated['phone'] ?? null,
                'qualification' => $validated['qualification'] ?? null,
                'specialization' => $specialization,
                'address' => $validated['address'] ?? null,
            ]);

            if (! empty($selectedClassrooms)) {
                $subjectId = null;
                if (! empty($validated['specializations'])) {
                    $firstSubjectName = reset($validated['specializations']);
                    $subjectId = Subject::where('name', $firstSubjectName)->value('id');
                }

                foreach ($selectedClassrooms as $classroomId) {
                    TeacherSubject::firstOrCreate([
                        'teacher_id' => $user->id,
                        'classroom_id' => $classroomId,
                        'subject_id' => $subjectId,
                    ]);
                }
            }
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Faculty account and profile created successfully! Default password is: password123');
    }

    public function edit(TeacherProfile $teacher)
    {
        $teacher->load(['user', 'taughtSubjects', 'taughtClassrooms']);
        $subjects = Subject::orderBy('name')->get();
        $classrooms = Classroom::orderBy('name')->get();

        // If subjects were deleted or changed, clean up orphaned specializations
        if ($teacher->specialization) {
            $existingSubjectNames = $subjects->pluck('name')->toArray();
            $specs = array_filter(array_map('trim', explode(',', (string) $teacher->specialization)));
            $validSpecs = array_values(array_filter($specs, fn ($s) => in_array($s, $existingSubjectNames, true)));

            if (count($specs) !== count($validSpecs)) {
                $teacher->update([
                    'specialization' => ! empty($validSpecs) ? implode(', ', $validSpecs) : null,
                ]);
                $teacher->refresh();
            }
        }

        return view('admin.teachers.edit', compact('teacher', 'subjects', 'classrooms'));
    }

    public function update(Request $request, TeacherProfile $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$teacher->user_id,
            'phone' => 'nullable|string|max:50',
            'qualification' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:500',
            'specializations' => 'nullable|array',
            'specializations.*' => 'string|max:255',
            'classrooms' => 'nullable|array',
            'classrooms.*' => 'exists:classrooms,id',
            'classroom_ids' => 'nullable|array',
            'classroom_ids.*' => 'exists:classrooms,id',
            'address' => 'nullable|string',
            'status' => 'nullable|in:active,inactive,suspended',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        $specialization = null;
        if (! empty($validated['specializations'])) {
            $specialization = implode(', ', array_filter($validated['specializations']));
        } elseif (! empty($validated['specialization'])) {
            $specialization = $validated['specialization'];
        }

        $selectedClassrooms = array_values(array_filter(array_map('intval', (array) ($request->input('classrooms') ?? $request->input('classroom_ids') ?? []))));

        $avatarPath = $teacher->avatar;
        if ($request->hasFile('avatar')) {
            if ($teacher->avatar && Storage::disk('public')->exists($teacher->avatar)) {
                Storage::disk('public')->delete($teacher->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars/teachers', 'public');
        }

        DB::transaction(function () use ($validated, $teacher, $specialization, $selectedClassrooms, $avatarPath) {
            $userUpdates = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (! empty($validated['status'])) {
                $userUpdates['status'] = $validated['status'];
            }

            $teacher->user->update($userUpdates);

            $teacher->update([
                'avatar' => $avatarPath,
                'phone' => $validated['phone'] ?? null,
                'qualification' => $validated['qualification'] ?? null,
                'specialization' => $specialization,
                'address' => $validated['address'] ?? null,
            ]);

            // Sync classroom assignments for this teacher
            $currentClassroomIds = TeacherSubject::where('teacher_id', $teacher->user_id)
                ->pluck('classroom_id')
                ->unique()
                ->toArray();

            // Classrooms to remove
            $toRemove = array_diff($currentClassroomIds, $selectedClassrooms);
            if (! empty($toRemove)) {
                TeacherSubject::where('teacher_id', $teacher->user_id)
                    ->whereIn('classroom_id', $toRemove)
                    ->delete();
            }

            // Classrooms to add
            $toAdd = array_diff($selectedClassrooms, $currentClassroomIds);
            if (! empty($toAdd)) {
                $subjectId = null;
                if (! empty($validated['specializations'])) {
                    $firstSubjectName = reset($validated['specializations']);
                    $subjectId = Subject::where('name', $firstSubjectName)->value('id');
                } elseif ($specialization) {
                    $specs = array_filter(array_map('trim', explode(',', $specialization)));
                    if (! empty($specs)) {
                        $subjectId = Subject::where('name', reset($specs))->value('id');
                    }
                }

                foreach ($toAdd as $classroomId) {
                    TeacherSubject::firstOrCreate([
                        'teacher_id' => $teacher->user_id,
                        'classroom_id' => $classroomId,
                        'subject_id' => $subjectId,
                    ]);
                }
            }
        });

        return redirect()->route('admin.teachers.index')->with('success', "Faculty member {$teacher->user->name} updated successfully!");
    }

    public function toggleStatus(TeacherProfile $teacher)
    {
        $currentStatus = $teacher->user?->status;
        $isSuspended = $currentStatus === TeacherStatus::Suspended || $currentStatus?->value === 'suspended' || $currentStatus === 'suspended';

        $newStatus = $isSuspended ? TeacherStatus::Active->value : TeacherStatus::Suspended->value;

        $teacher->user->update([
            'status' => $newStatus,
        ]);

        $name = $teacher->user->name ?? 'Faculty member';
        $message = $newStatus === TeacherStatus::Suspended->value
            ? "Faculty member {$name} has been suspended."
            : "Faculty member {$name} has been reinstated and activated.";

        return back()->with('success', $message);
    }

    public function destroy(TeacherProfile $teacher)
    {
        $name = $teacher->user->name ?? 'Faculty member';

        DB::transaction(function () use ($teacher) {
            if ($teacher->avatar && Storage::disk('public')->exists($teacher->avatar)) {
                Storage::disk('public')->delete($teacher->avatar);
            }

            $user = $teacher->user;
            $teacher->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.teachers.index')->with('success', "Faculty member {$name} has been deleted.");
    }
}
