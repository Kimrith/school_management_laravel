<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\FeeController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Students
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::get('/students/insert', [StudentController::class, 'create'])->name('students.insert');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');
Route::get('/students/suspended', [StudentController::class, 'suspended'])->name('students.suspended');
Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
Route::patch('/students/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggle-status');
Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

// Teachers
Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
Route::get('/teachers/insert', [TeacherController::class, 'create'])->name('teachers.insert');
Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
Route::get('/teachers/suspended', [TeacherController::class, 'suspended'])->name('teachers.suspended');
Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
Route::patch('/teachers/{teacher}/toggle-status', [TeacherController::class, 'toggleStatus'])->name('teachers.toggle-status');
Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

// Classes
Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
Route::get('/classes/create', [ClassController::class, 'create'])->name('classes.create');
Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
Route::get('/classes/suspended', [ClassController::class, 'suspended'])->name('classes.suspended');
Route::get('/classes/{classroom}/edit', [ClassController::class, 'edit'])->name('classes.edit');
Route::put('/classes/{classroom}', [ClassController::class, 'update'])->name('classes.update');
Route::patch('/classes/{classroom}/toggle-status', [ClassController::class, 'toggleStatus'])->name('classes.toggle-status');
Route::delete('/classes/{classroom}', [ClassController::class, 'destroy'])->name('classes.destroy');

// Academic Levels
Route::get('/levels', [LevelController::class, 'index'])->name('levels.index');
Route::get('/levels/create', [LevelController::class, 'create'])->name('levels.create');
Route::post('/levels', [LevelController::class, 'store'])->name('levels.store');
Route::get('/levels/suspended', [LevelController::class, 'suspended'])->name('levels.suspended');
Route::get('/levels/{level}/edit', [LevelController::class, 'edit'])->name('levels.edit');
Route::put('/levels/{level}', [LevelController::class, 'update'])->name('levels.update');
Route::patch('/levels/{level}/toggle-status', [LevelController::class, 'toggleStatus'])->name('levels.toggle-status');
Route::delete('/levels/{level}', [LevelController::class, 'destroy'])->name('levels.destroy');

Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
Route::get('/fees', [FeeController::class, 'index'])->name('fees.index');
Route::post('/fees', [FeeController::class, 'store'])->name('fees.store');
Route::patch('/fees/{fee}/status', [FeeController::class, 'updateStatus'])->name('fees.update-status');

// Subjects
Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.delete');

Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
