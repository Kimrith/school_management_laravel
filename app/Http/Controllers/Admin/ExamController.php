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

        $exams = $query->paginate(7)->withQueryString();
        $subjects = Subject::orderBy('name')->get();
        $classrooms = Classroom::orderBy('name')->get();

        return view('admin.exams.index', compact('exams', 'subjects', 'classrooms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'exam_date' => 'required|date',
            'total_marks' => 'required|numeric|min:0',
        ]);

        Exam::create($validated);

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam scheduled successfully.');
    }
}
