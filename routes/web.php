<?php

use App\Models\StudentProfile;
use Illuminate\Support\Facades\Route;

// Global Redirects
Route::redirect('/', '/student/dashboard');
Route::redirect('/dashboard', '/admin/dashboard');
Route::redirect('/admin', '/admin/dashboard');
Route::redirect('/teacher', '/teacher/dashboard');
Route::redirect('/student', '/student/dashboard');

// Module Routes
Route::prefix('admin')->name('admin.')->group(base_path('routes/admin/web.php'));
Route::prefix('teacher')->name('teacher.')->group(base_path('routes/teacher/web.php'));
Route::prefix('student')->name('student.')->group(base_path('routes/student/web.php'));

// Shared / PDF Routes
Route::get('/pdf/report-card', function () {
    $student = StudentProfile::with(['user', 'classroom'])->first();

    return view('pdf.report-card', [
        'student' => $student,
        'classroom' => $student?->classroom,
    ]);
})->name('pdf.report-card');
