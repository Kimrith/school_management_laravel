<?php

use App\Models\Classroom;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        $totalStudents = StudentProfile::count();
        $totalTeachers = TeacherProfile::count();
        $totalClassrooms = Classroom::count();

        return view('admin.dashboard', [
            'totalStudents' => $totalStudents > 0 ? $totalStudents : 1284,
            'totalTeachers' => $totalTeachers > 0 ? $totalTeachers : 86,
            'totalClassrooms' => $totalClassrooms > 0 ? $totalClassrooms : 34,
            'attendanceRate' => '96.8%',
        ]);
    })->name('admin.dashboard');

    Route::get('/students', function () {
        return view('admin.students.index');
    })->name('admin.students.index');

    Route::get('/teachers', function () {
        return view('admin.teachers.index');
    })->name('admin.teachers.index');

    Route::get('/classes', function () {
        return view('admin.classes.index');
    })->name('admin.classes.index');

    Route::get('/attendances', function () {
        return view('admin.attendances.index');
    })->name('admin.attendances.index');

    Route::get('/fees', function () {
        return view('admin.fees.index');
    })->name('admin.fees.index');

    Route::get('/subjects', function () {
        return view('admin.subjects.index');
    })->name('admin.subjects.index');

    Route::get('/exams', function () {
        return view('admin.exams.index');
    })->name('admin.exams.index');
});

Route::redirect('/admin', '/admin/dashboard');
Route::redirect('/dashboard', '/admin/dashboard');

// Teacher Portal Routes
Route::prefix('teacher')->group(function () {
    Route::get('/dashboard', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard');

    Route::get('/attendance', function () {
        return view('teacher.attendance.index');
    })->name('teacher.attendance.index');

    Route::get('/grades', function () {
        return view('teacher.grades.index');
    })->name('teacher.grades.index');
});

Route::redirect('/teacher', '/teacher/dashboard');

// Student Portal Routes
Route::prefix('student')->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
});

Route::redirect('/student', '/student/dashboard');

// PDF Printable Route
Route::get('/pdf/report-card', function () {
    $student = StudentProfile::with(['user', 'classroom'])->first();

    return view('pdf.report-card', [
        'student' => $student,
        'classroom' => $student?->classroom,
    ]);
})->name('pdf.report-card');
