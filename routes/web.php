<?php

use App\Enums\Role;
use App\Http\Controllers\Auth\AuthController;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Route;

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Global dashboard redirect
    Route::get('/dashboard', function () {
        return match (auth()->user()?->role) {
            Role::Admin => redirect()->route('admin.dashboard'),
            Role::Teacher => redirect()->route('teacher.dashboard'),
            Role::Student => redirect()->route('student.dashboard'),
            default => redirect()->route('login'),
        };
    })->name('dashboard');

    Route::redirect('/admin', '/admin/dashboard');
    Route::redirect('/teacher', '/teacher/dashboard');
    Route::redirect('/student', '/student/dashboard');

    // Protected Module Routes
    Route::prefix('admin')->name('admin.')->group(base_path('routes/admin/web.php'));
    Route::prefix('teacher')->name('teacher.')->group(base_path('routes/teacher/web.php'));
    Route::prefix('student')->name('student.')->group(base_path('routes/student/web.php'));
});

// Root URL: redirects to login if guest, or dashboard if authenticated
Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return redirect('/dashboard');
});

// Shared / PDF Routes
Route::get('/pdf/report-card', function () {
    $student = StudentProfile::with(['user', 'classroom'])->first();

    return view('pdf.report-card', [
        'student' => $student,
        'classroom' => $student?->classroom,
    ]);
})->name('pdf.report-card');
