<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
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

        $classrooms = ($user && $user->role === Role::Admin)
            ? Classroom::orderBy('name')->get()
            : ($user ? Classroom::forTeacher($user)->orderBy('name')->get() : collect());

        return view('teacher.grades.index', [
            'classrooms' => $classrooms,
        ]);
    }
}
