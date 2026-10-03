<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display student academic performance scores and examination reports.
     */
    public function index(Request $request): View
    {
        $query = Mark::with([
            'student.user',
            'student.classroom',
            'exam.subject',
            'exam.classroom',
        ]);

        // Filter by classroom (checks student classroom or exam classroom)
        if ($request->filled('classroom_id') && $request->classroom_id !== 'all') {
            $classroomId = (int) $request->classroom_id;
            $query->where(function ($q) use ($classroomId) {
                $q->whereHas('student', function ($sq) use ($classroomId) {
                    $sq->where('classroom_id', $classroomId);
                })->orWhereHas('exam', function ($eq) use ($classroomId) {
                    $eq->where('classroom_id', $classroomId);
                });
            });
        }

        // Filter by examination title
        if ($request->filled('exam_title') && $request->exam_title !== 'all') {
            $examTitle = $request->string('exam_title')->trim();
            $query->whereHas('exam', function ($q) use ($examTitle) {
                $q->where('title', $examTitle);
            });
        }

        // Filter by subject
        if ($request->filled('subject_id') && $request->subject_id !== 'all') {
            $subjectId = (int) $request->subject_id;
            $query->whereHas('exam', function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            });
        }

        // Filter by letter grade or pass/fail status
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'passed') {
                $query->where('marks_obtained', '>=', 50);
            } elseif ($status === 'failed') {
                $query->where('marks_obtained', '<', 50);
            } elseif (in_array($status, ['A', 'B+', 'B', 'C+', 'C', 'D', 'F'], true)) {
                $query->where('grade_letter', $status);
            }
        }

        // Keyword search (student name, student code, exam title, subject name)
        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where(function ($q) use ($search) {
                $q->whereHas('student.user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('student_code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('exam', function ($eq) use ($search) {
                        $eq->where('title', 'like', "%{$search}%")
                            ->orWhereHas('subject', function ($sub) use ($search) {
                                $sub->where('name', 'like', "%{$search}%")
                                    ->orWhere('code', 'like', "%{$search}%");
                            });
                    });
            });
        }

        // Calculate KPI Metrics across the filtered dataset
        $allMatching = (clone $query)->get();
        $totalMarksCount = $allMatching->count();
        $averageScore = $totalMarksCount > 0 ? round($allMatching->avg('marks_obtained'), 1) : 0;
        $highestScore = $totalMarksCount > 0 ? (float) $allMatching->max('marks_obtained') : 0;
        $passedCount = $allMatching->where('marks_obtained', '>=', 50)->count();
        $passRate = $totalMarksCount > 0 ? round(($passedCount / $totalMarksCount) * 100, 1) : 0;

        // Paginate records
        $marks = $query->latest('updated_at')->paginate(12)->withQueryString();

        // Eager-loaded dropdown filters
        $classrooms = Classroom::orderBy('name')->get();
        $examTitles = Exam::distinct()->orderBy('title')->pluck('title');
        $subjects = Subject::orderBy('name')->get();

        return view('admin.report.index', compact(
            'marks',
            'classrooms',
            'examTitles',
            'subjects',
            'totalMarksCount',
            'averageScore',
            'highestScore',
            'passedCount',
            'passRate'
        ));
    }
}
