<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjectsList = Subject::with(['teachers', 'classrooms'])->get()->map(function ($subject) {
            return [
                'id' => $subject->id,
                'name' => $subject->name,
                'code' => $subject->code,
                'desc' => $subject->description,
                'credits' => $subject->credits ?? 3,
                'teachers' => $subject->teachers->isNotEmpty()
                    ? $subject->teachers->pluck('name')->toArray()
                    : ['TBD'],
                'classes' => $subject->classrooms->pluck('name')->toArray() ?? [],
            ];
        });

        $teachers = TeacherProfile::with('user')->get();

        return view('admin.subjects.index', compact('subjectsList', 'teachers'));
    }

    public function create()
    {
        $teachers = TeacherProfile::with('user')->get();

        return view('admin.subjects.insert', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects,code',
            'teacher_id' => 'nullable|exists:teacher_profiles,id',
            'credits' => 'nullable|integer|min:1|max:6',
            'description' => 'nullable|string',
        ]);

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'Subject created successfully!');
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects,code,'.$subject->id,
            'teacher_id' => 'nullable|exists:teacher_profiles,id',
            'credits' => 'nullable|integer|min:1|max:6',
            'description' => 'nullable|string',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully!');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully!');
    }
}
