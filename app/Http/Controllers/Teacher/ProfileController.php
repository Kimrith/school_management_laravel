<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the teacher profile page.
     */
    public function index(): View
    {
        $user = auth()->user();

        /** @var TeacherProfile|null $teacher */
        $teacher = $user?->teacherProfile;

        // Ensure teacher profile belongs strictly to the authenticated user
        if (! $teacher && $user) {
            $teacher = TeacherProfile::firstOrCreate(['user_id' => $user->id]);
        }

        $teacher?->loadMissing(['user', 'taughtSubjects', 'taughtClassrooms.studentProfiles', 'teacherSubjects.subject', 'teacherSubjects.classroom.studentProfiles']);

        // Active classrooms assigned to this teacher
        $assignedClassrooms = $teacher?->taughtClassrooms?->unique('id') ?? collect();
        $assignedSubjects = $teacher?->taughtSubjects?->unique('id') ?? collect();
        $teacherSubjects = $teacher?->teacherSubjects ?? collect();

        // Calculate assigned students count
        $classroomIds = $assignedClassrooms->pluck('id')->filter()->all();
        $totalStudents = ! empty($classroomIds)
            ? StudentProfile::whereIn('classroom_id', $classroomIds)->count()
            : 0;

        $teachingLoadHours = $teacherSubjects->count() * 3;

        return view('teacher.profile.index', [
            'teacher' => $teacher,
            'user' => $teacher?->user ?? $user,
            'assignedClassrooms' => $assignedClassrooms,
            'assignedSubjects' => $assignedSubjects,
            'teacherSubjects' => $teacherSubjects,
            'totalStudents' => $totalStudents,
            'teachingLoadHours' => $teachingLoadHours,
        ]);
    }

    /**
     * Update the teacher's profile details.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        if ($user) {
            $user->update([
                'name' => $validated['name'],
            ]);

            TeacherProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $validated['phone'] ?? null,
                    'qualification' => $validated['qualification'] ?? null,
                    'specialization' => $validated['specialization'] ?? null,
                    'address' => $validated['address'] ?? null,
                ]
            );
        }

        return redirect()->route('teacher.profile.index')
            ->with('success', 'Faculty profile information updated successfully!');
    }

    /**
     * Update the teacher's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($user) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return redirect()->route('teacher.profile.index')
            ->with('success', 'Account security credentials updated successfully!');
    }
}
