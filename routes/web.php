<?php

use App\Enums\Role;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ReportCardController;
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
    Route::redirect('/admin', '/admin/dashboard')->middleware('role:admin');
    Route::redirect('/teacher', '/teacher/dashboard')->middleware('role:teacher');
    Route::redirect('/student', '/student/dashboard')->middleware('role:student');

    // Protected Module Routes
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:admin')
        ->group(base_path('routes/admin/web.php'));

    Route::prefix('teacher')
        ->name('teacher.')
        ->middleware('role:teacher')
        ->group(base_path('routes/teacher/web.php'));

    Route::prefix('student')
        ->name('student.')
        ->middleware('role:student')
        ->group(base_path('routes/student/web.php'));
});

// Root URL: redirects to login if guest, or dashboard if authenticated
Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return redirect('/dashboard');
});

// Shared / PDF Routes
Route::get('/pdf/report-card/{student?}', [ReportCardController::class, 'show'])->name('pdf.report-card');
