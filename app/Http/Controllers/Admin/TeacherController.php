<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Enums\TeacherStatus;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        return view('admin.teachers.index', compact('teachers', 'counts', 'statusFilter', 'subjects'));
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
            'address' => 'nullable|string',
        ]);

        $specialization = null;
        if (! empty($validated['specializations'])) {
            $specialization = implode(', ', array_filter($validated['specializations']));
        } elseif (! empty($validated['specialization'])) {
            $specialization = $validated['specialization'];
        }

        DB::transaction(function () use ($validated, $specialization) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make('password123'),
                'role' => Role::Teacher,
                'status' => TeacherStatus::Active->value,
            ]);

            TeacherProfile::create([
                'user_id' => $user->id,
                'phone' => $validated['phone'] ?? null,
                'qualification' => $validated['qualification'] ?? null,
                'specialization' => $specialization,
                'address' => $validated['address'] ?? null,
            ]);
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Faculty account and profile created successfully! Default password is: password123');
    }

    public function edit(TeacherProfile $teacher)
    {
        $teacher->load(['user', 'taughtSubjects', 'taughtClassrooms']);
        $subjects = Subject::orderBy('name')->get();

        return view('admin.teachers.edit', compact('teacher', 'subjects'));
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
            'address' => 'nullable|string',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);

        $specialization = null;
        if (! empty($validated['specializations'])) {
            $specialization = implode(', ', array_filter($validated['specializations']));
        } elseif (! empty($validated['specialization'])) {
            $specialization = $validated['specialization'];
        }

        DB::transaction(function () use ($validated, $teacher, $specialization) {
            $userUpdates = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (! empty($validated['status'])) {
                $userUpdates['status'] = $validated['status'];
            }

            $teacher->user->update($userUpdates);

            $teacher->update([
                'phone' => $validated['phone'] ?? null,
                'qualification' => $validated['qualification'] ?? null,
                'specialization' => $specialization,
                'address' => $validated['address'] ?? null,
            ]);
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
            $user = $teacher->user;
            $teacher->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.teachers.index')->with('success', "Faculty member {$name} has been deleted.");
    }
}
