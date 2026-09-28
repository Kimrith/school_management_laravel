<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        // Fetch all classrooms with student counts
        $classrooms = Classroom::withCount('studentProfiles')->latest()->paginate(10);

        return view('admin.classes.index', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
            'academic_year' => 'required|string|max:50',
        ]);

        Classroom::create($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Classroom crea
            ted successfully!');
    }
}
