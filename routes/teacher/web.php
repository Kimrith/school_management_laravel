<?php

use App\Http\Controllers\Teacher\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', fn () => view('teacher.dashboard'))->name('dashboard');
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
Route::get('/grades', fn () => view('teacher.grades.index'))->name('grades.index');
