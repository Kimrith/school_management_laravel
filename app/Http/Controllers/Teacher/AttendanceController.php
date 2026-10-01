<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\StudentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Display the teacher attendance roster sheet.
     */
    public function index(Request $request): View
    {
        $classrooms = Classroom::orderBy('name')->get();

        $selectedClassroomId = $request->input('classroom_id', $classrooms->first()?->id);
        $selectedClassroom = $classrooms->firstWhere('id', (int) $selectedClassroomId) ?? $classrooms->first();
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

        if (! empty($validated['attendances'])) {
            foreach ($validated['attendances'] as $record) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $record['student_id'],
                        'date' => $validated['date'],
                    ],
                    [
                        'classroom_id' => $validated['classroom_id'],
                        'status' => $record['status'],
                        'remarks' => $record['remarks'] ?? null,
                    ]
                );
            }
        }

        return redirect()
            ->route('teacher.attendance.index', [
                'classroom_id' => $validated['classroom_id'],
                'date' => $validated['date'],
            ])
            ->with('success', 'Attendance submitted successfully! Records have been pushed to the Admin Dashboard.');
    }
}
