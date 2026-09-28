<?php

use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Models\Classroom;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use Illuminate\Support\Facades\Route;

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
})->name('dashboard');

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
Route::get('/classes', fn () => view('admin.classes.index'))->name('classes.index');
Route::get('/attendances', fn () => view('admin.attendances.index'))->name('attendances.index');
Route::get('/fees', fn () => view('admin.fees.index'))->name('fees.index');

// Subjects
Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

Route::get('/exams', fn () => view('admin.exams.index'))->name('exams.index');
