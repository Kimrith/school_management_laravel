<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\StudentProfile;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the teacher faculty dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $assignedClassrooms = ($user && $user->role === Role::Admin)
            ? Classroom::orderBy('name')->get()
            : ($user ? Classroom::forTeacher($user)->orderBy('name')->get() : collect());

        $classroomIds = $assignedClassrooms->pluck('id')->all();

        $totalStudents = ! empty($classroomIds)
            ? StudentProfile::whereIn('classroom_id', $classroomIds)->count()
            : 0;

        $teacherSubjects = $user
            ? TeacherSubject::with(['classroom', 'subject'])
                ->where('teacher_id', $user->id)
                ->get()
            : collect();

        return view('teacher.dashboard', [
            'assignedClassrooms' => $assignedClassrooms,
            'totalStudents' => $totalStudents,
            'teacherSubjects' => $teacherSubjects,
        ]);
    }
}
