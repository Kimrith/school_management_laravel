<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function index(Request $request): View
    {
        $query = Exam::with(['subject', 'classroom'])->latest();

        if ($request->filled('classroom_id') && $request->classroom_id !== 'all') {
            $query->where('classroom_id', $request->classroom_id);
        }

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('subject', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('classroom', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $exams = $query->paginate(20)->withQueryString();
        $groupedExams = $exams->getCollection()->groupBy('title');
        $subjects = Subject::orderBy('name')->get();
        $classrooms = Classroom::orderBy('name')->get();
        $existingTitles = Exam::distinct()->orderBy('title')->pluck('title');

        return view('admin.exams.index', compact('exams', 'groupedExams', 'subjects', 'classrooms', 'existingTitles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
            'exam_date' => 'required|date',
            'total_marks' => 'required|numeric|min:0',
        ]);

        $title = trim($validated['title']);
        $classroomId = (int) $validated['classroom_id'];

        // Determine list of subjects to assign
        $subjectIds = ! empty($validated['subject_ids'])
            ? array_filter($validated['subject_ids'])
            : ($validated['subject_id'] ? [$validated['subject_id']] : []);

        if (empty($subjectIds)) {
            return back()->withErrors([
                'subject_id' => 'Please select at least one subject to assign to this exam.',
            ])->withInput();
        }

        $createdCount = 0;
        foreach ($subjectIds as $subjectId) {
            $exists = Exam::where('title', $title)
                ->where('classroom_id', $classroomId)
                ->where('subject_id', $subjectId)
                ->exists();

            if (! $exists) {
                Exam::create([
                    'title' => $title,
                    'classroom_id' => $classroomId,
                    'subject_id' => $subjectId,
                    'exam_date' => $validated['exam_date'],
                    'total_marks' => $validated['total_marks'],
                ]);
                $createdCount++;
            }
        }

        if ($createdCount === 0) {
            return redirect()->route('admin.exams.index')
                ->with('info', "The selected subjects are already assigned to '{$title}' for this classroom.");
        }

        $message = $createdCount > 1
            ? "Successfully assigned {$createdCount} subjects to '{$title}'."
            : "Successfully assigned subject to '{$title}'.";

        return redirect()->route('admin.exams.index')
            ->with('success', $message);
    }

    public function destroy(Exam $exam): RedirectResponse
    {
        $title = $exam->title;
        $subjectName = $exam->subject?->name ?? 'Subject';

        $exam->delete();

        return redirect()->route('admin.exams.index')
            ->with('success', "Removed {$subjectName} from '{$title}'.");
    }
}
