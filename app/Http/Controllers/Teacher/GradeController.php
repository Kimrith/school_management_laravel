<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\StudentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    /**
     * Display the teacher grades entry page.
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
            $selectedClassroom = $classrooms->firstWhere('id', (int) $selectedClassroomId)
                ?? Classroom::find((int) $selectedClassroomId);
        } else {
            $selectedClassroom = $classrooms->first() ?? Classroom::first();
        }

        $selectedClassroomId = $selectedClassroom?->id;

        // Fetch exams related to the classroom or all exams
        $exams = $selectedClassroomId
            ? Exam::with(['subject', 'classroom'])->where('classroom_id', $selectedClassroomId)->orderByDesc('exam_date')->get()
            : collect();

        if ($exams->isEmpty()) {
            $exams = Exam::with(['subject', 'classroom'])->orderByDesc('exam_date')->get();
        }

        // Group exams by title so identical titles are not repeated
        $groupedExams = $exams->groupBy('title');
        $examTitles = $groupedExams->keys();

        $selectedExamId = $request->input('exam_id');
        $selectedExam = null;

        if ($selectedExamId !== null && $selectedExamId !== '') {
            $selectedExam = $exams->firstWhere('id', (int) $selectedExamId) ?? Exam::find((int) $selectedExamId);
        }

        $selectedExamTitle = $request->input('exam_title') ?? $selectedExam?->title ?? $examTitles->first();

        // All subject exams assigned under this title
        $titleSubjectExams = $groupedExams->get($selectedExamTitle, collect());

        // If no exam selected or selected exam belongs to a different title, pick the first subject of this title
        if (! $selectedExam || $selectedExam->title !== $selectedExamTitle) {
            $selectedExam = $titleSubjectExams->first();
        }

        $selectedExamId = $selectedExam?->id;

        // Fetch students in this classroom
        $students = $selectedClassroomId
            ? StudentProfile::with('user')
                ->where('classroom_id', $selectedClassroomId)
                ->orderBy('id')
                ->get()
            : collect();

        // Fallback to all students if no students assigned to this classroom yet
        if ($students->isEmpty() && StudentProfile::count() > 0) {
            $students = StudentProfile::with(['user', 'classroom'])->orderBy('id')->get();
        }

        // Fetch existing marks for this exam
        $existingMarks = $selectedExamId
            ? Mark::where('exam_id', $selectedExamId)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id')
            : collect();

        // Format payload for Alpine.js table
        $studentsPayload = $students->map(function (StudentProfile $student) use ($existingMarks) {
            $mark = $existingMarks->get($student->id);

            return [
                'id' => $student->id,
                'code' => $student->student_code ?? 'STU-'.$student->id,
                'name' => $student->user?->name ?? 'Student #'.$student->id,
                'marks' => $mark ? (float) $mark->marks_obtained : null,
                'grade' => $mark?->grade_letter ?? '',
            ];
        })->values();

        return view('teacher.grades.index', [
            'classrooms' => $classrooms,
            'selectedClassroom' => $selectedClassroom,
            'selectedClassroomId' => $selectedClassroomId,
            'exams' => $exams,
            'groupedExams' => $groupedExams,
            'examTitles' => $examTitles,
            'selectedExamTitle' => $selectedExamTitle,
            'titleSubjectExams' => $titleSubjectExams,
            'selectedExam' => $selectedExam,
            'selectedExamId' => $selectedExamId,
            'students' => $students,
            'studentsPayload' => $studentsPayload,
        ]);
    }

    /**
     * Store or update marks for the students.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'marks' => 'nullable|array',
            'marks.*.student_id' => 'required|exists:student_profiles,id',
            'marks.*.marks_obtained' => 'nullable|numeric|min:0|max:100',
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);

        if (! empty($validated['marks'])) {
            foreach ($validated['marks'] as $row) {
                if (! isset($row['student_id'])) {
                    continue;
                }

                $score = $row['marks_obtained'] !== null && $row['marks_obtained'] !== ''
                    ? (float) $row['marks_obtained']
                    : null;

                if ($score === null) {
                    continue;
                }

                $gradeLetter = $this->calculateGradeLetter($score);

                Mark::updateOrCreate(
                    [
                        'exam_id' => $exam->id,
                        'student_id' => $row['student_id'],
                    ],
                    [
                        'marks_obtained' => $score,
                        'grade_letter' => $gradeLetter,
                    ]
                );
            }
        }

        return redirect()->route('teacher.grades.index', [
            'classroom_id' => $validated['classroom_id'] ?? $exam->classroom_id,
            'exam_title' => $exam->title,
            'exam_id' => $exam->id,
        ])->with('success', 'All grades saved and published successfully!');
    }

    /**
     * Helper to compute letter grade based on numeric score.
     */
    protected function calculateGradeLetter(float $score): string
    {
        return match (true) {
            $score >= 90 => 'A',
            $score >= 85 => 'B+',
            $score >= 80 => 'B',
            $score >= 70 => 'C+',
            $score >= 65 => 'C',
            $score >= 50 => 'D',
            default => 'F',
        };
    }
}
