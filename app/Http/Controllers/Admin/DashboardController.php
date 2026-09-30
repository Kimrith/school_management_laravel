<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = StudentProfile::count();
        $totalTeachers = TeacherProfile::count();
        $totalClassrooms = Classroom::count();

        // Optional: you can pass these variables to your dashboard view
        return view('admin.dashboard.index', compact(
            'totalStudents',
            'totalTeachers',
            'totalClassrooms'
        ));
    }
}
