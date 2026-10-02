<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\StudentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Display the teacher attendance roster sheet.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Scope to classrooms assigned to the authenticated teacher (Admins have access to all)
        $classrooms = ($user && $user->role === Role::Admin)
            ? Classroom::with('studentProfiles')->orderBy('name')->get()
            : ($user ? Classroom::forTeacher($user)->with('studentProfiles')->orderBy('name')->get() : collect());

        $selectedClassroomId = $request->input('classroom_id');
        $selectedClassroom = null;

        if ($selectedClassroomId !== null && $selectedClassroomId !== '') {
            $classroom = Classroom::findOrFail((int) $selectedClassroomId);
            Gate::authorize('viewAttendance', $classroom);
            $selectedClassroom = $classroom;
        } else {
            $selectedClassroom = $classrooms->first();
        }

        $date = $request->input('date', now()->format('Y-m-d'));

        $students = collect();
        $studentsPayload = collect();

        if ($selectedClassroom) {
            $students = StudentProfile::with('user')
                ->where('classroom_id', $selectedClassroom->id)
                ->orderBy('id')
                ->get();

            $existingAttendances = Attendance::where('classroom_id', $selectedClassroom->id)
                ->whereDate('date', $date)
                ->get()
                ->keyBy('student_id');

            $studentsPayload = $students->map(function (StudentProfile $student) use ($existingAttendances) {
                $record = $existingAttendances->get($student->id);

                return [
                    'id' => $student->id,
                    'code' => $student->student_code,
                    'name' => $student->user?->name ?? 'Student #'.$student->id,
                    'status' => $record?->status ?? 'present',
                    'remarks' => $record?->remarks ?? '',
                ];
            });
        }

        return view('teacher.attendance.index', [
            'classrooms' => $classrooms,
            'selectedClassroom' => $selectedClassroom,
            'selectedClassroomId' => $selectedClassroom?->id,
            'date' => $date,
            'students' => $students,
            'studentsPayload' => $studentsPayload,
        ]);
    }

    /**
     * Store or update attendance records for the selected classroom.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'date' => 'required|date',
            'attendances' => 'nullable|array',
            'attendances.*.student_id' => 'required|exists:student_profiles,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'attendances.*.remarks' => 'nullable|string|max:500',
        ]);

        $classroom = Classroom::findOrFail($validated['classroom_id']);
        Gate::authorize('recordAttendance', $classroom);

        if (! empty($validated['attendances'])) {
            $validStudentIds = StudentProfile::where('classroom_id', $classroom->id)
                ->whereIn('id', collect($validated['attendances'])->pluck('student_id'))
                ->pluck('id')
                ->flip();

            foreach ($validated['attendances'] as $record) {
                if (! isset($validStudentIds[$record['student_id']])) {
                    continue;
                }

                Attendance::updateOrCreate(
                    [
                        'student_id' => $record['student_id'],
                        'date' => $validated['date'],
                    ],
                    [
                        'classroom_id' => $classroom->id,
                        'status' => $record['status'],
                        'remarks' => $record['remarks'] ?? null,
                    ]
                );
            }
        }

        return redirect()
            ->route('teacher.attendance.index', [
                'classroom_id' => $classroom->id,
                'date' => $validated['date'],
            ])
            ->with('success', 'Attendance submitted successfully! Records have been pushed to the Admin Dashboard.');
    }
}
