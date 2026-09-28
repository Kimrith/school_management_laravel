<?php

use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', fn () => view('student.dashboard'))->name('dashboard');

Route::get('/profile', function () {
    $student = auth()->user()?->studentProfile
        ?? StudentProfile::with(['user', 'classroom', 'marks.exam.subject', 'attendances', 'feeInvoices'])->first();

    return view('student.profile.index', [
        'student' => $student,
    ]);
})->name('profile.index');

Route::put('/profile', function (Request $request) {
    $student = auth()->user()?->studentProfile ?? StudentProfile::first();
    if ($student) {
        $validated = $request->validate([
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date',
        ]);

        $student->update([
            'parent_name' => $validated['parent_name'] ?? $student->parent_name,
            'parent_phone' => $validated['parent_phone'] ?? $student->parent_phone,
            'address' => $validated['address'] ?? $student->address,
            'gender' => $validated['gender'] ?? $student->gender,
            'date_of_birth' => $validated['dob'] ?? $student->date_of_birth,
        ]);

        if ($request->filled('name') && $student->user) {
            $student->user->update(['name' => $request->string('name')->trim()]);
        }
    }

    return redirect()->route('student.profile.index')->with('success', 'Profile information updated successfully!');
})->name('profile.update');
