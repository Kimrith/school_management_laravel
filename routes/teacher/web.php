<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', fn () => view('teacher.dashboard'))->name('dashboard');
Route::get('/attendance', fn () => view('teacher.attendance.index'))->name('attendance.index');
Route::get('/grades', fn () => view('teacher.grades.index'))->name('grades.index');
