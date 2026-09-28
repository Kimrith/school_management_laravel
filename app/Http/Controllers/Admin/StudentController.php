<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Enums\StudentStatus;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'all');

        $query = StudentProfile::with(['user', 'classroom']);

        if ($statusFilter !== 'all' && in_array($statusFilter, StudentStatus::values(), true)) {
            $query->whereHas('user', function ($q) use ($statusFilter) {
                $q->where('status', $statusFilter);
            });
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->trim();
            $query->where(function ($q) use ($search) {
                $q->where('student_code', 'like', "%{$search}%")
                    ->orWhere('parent_name', 'like', "%{$search}%")
                    ->orWhere('parent_phone', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->input('classroom_id'));
        }

        $students = $query->latest()->paginate(7)->withQueryString();
        $classrooms = Classroom::all();

        $counts = [
            'all' => StudentProfile::count(),
            'active' => StudentProfile::whereHas('user', fn ($q) => $q->where('status', StudentStatus::Active->value))->count(),
            'pending' => StudentProfile::whereHas('user', fn ($q) => $q->where('status', StudentStatus::Pending->value))->count(),
            'inactive' => StudentProfile::whereHas('user', fn ($q) => $q->where('status', StudentStatus::Inactive->value))->count(),
            'suspended' => StudentProfile::whereHas('user', fn ($q) => $q->where('status', StudentStatus::Suspended->value))->count(),
        ];

        return view('admin.students.index', compact('students', 'classrooms', 'counts', 'statusFilter'));
    }

    public function suspended(Request $request)
    {
        $query = StudentProfile::with(['user', 'classroom'])
            ->whereHas('user', function ($q) {
                $q->where('status', StudentStatus::Suspended->value);
            });

        if ($request->filled('search')) {
            $search = $request->string('search')->trim();
            $query->where(function ($q) use ($search) {
                $q->where('student_code', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $students = $query->latest()->paginate(7)->withQueryString();
        $activeCount = StudentProfile::whereHas('user', fn ($q) => $q->where('status', '!=', StudentStatus::Suspended->value))->count();
        $suspendedCount = StudentProfile::whereHas('user', fn ($q) => $q->where('status', StudentStatus::Suspended->value))->count();

        return view('admin.students.suspended', compact('students', 'activeCount', 'suspendedCount'));
    }

    public function create()
    {
        return redirect()->route('admin.students.index', ['create' => 1]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'student_code' => 'required|string|unique:student_profiles',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make('password123'),
                'role' => Role::Student,
                'status' => StudentStatus::Active,
            ]);

            StudentProfile::create([
                'user_id' => $user->id,
                'classroom_id' => $validated['classroom_id'],
                'student_code' => $validated['student_code'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'parent_name' => $validated['parent_name'],
                'parent_phone' => $validated['parent_phone'],
                'address' => $validated['address'],
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Student account and profile created successfully! Default password is: password123');
    }

    public function edit(StudentProfile $student)
    {
        $student->load(['user', 'classroom']);
        $classrooms = Classroom::all();

        return view('admin.students.edit', compact('student', 'classrooms'));
    }

    public function update(Request $request, StudentProfile $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$student->user_id,
            'student_code' => 'required|string|unique:student_profiles,student_code,'.$student->id,
            'classroom_id' => 'nullable|exists:classrooms,id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'status' => 'nullable|in:active,inactive,pending,suspended',
        ]);

        DB::transaction(function () use ($validated, $student) {
            $userUpdates = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (! empty($validated['status'])) {
                $userUpdates['status'] = $validated['status'];
            }

            $student->user->update($userUpdates);

            $student->update([
                'classroom_id' => $validated['classroom_id'],
                'student_code' => $validated['student_code'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'parent_name' => $validated['parent_name'],
                'parent_phone' => $validated['parent_phone'],
                'address' => $validated['address'],
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', "Student {$student->student_code} updated successfully!");
    }

    public function toggleStatus(StudentProfile $student)
    {
        $currentStatus = $student->user?->status;
        $isSuspended = $currentStatus === StudentStatus::Suspended || $currentStatus?->value === 'suspended' || $currentStatus === 'suspended';

        $newStatus = $isSuspended ? StudentStatus::Active : StudentStatus::Suspended;

        $student->user->update([
            'status' => $newStatus,
        ]);

        $message = $newStatus === StudentStatus::Suspended
            ? "Student {$student->student_code} has been suspended."
            : "Student {$student->student_code} has been reinstated and activated.";

        return back()->with('success', $message);
    }

    public function destroy(StudentProfile $student)
    {
        $code = $student->student_code;

        DB::transaction(function () use ($student) {
            $user = $student->user;
            $student->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.students.index')->with('success', "Student {$code} has been deleted.");
    }
}
