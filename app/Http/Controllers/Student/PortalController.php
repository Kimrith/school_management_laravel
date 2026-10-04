<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends Controller
{
    /**
     * Display the student academic dashboard.
     */
    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $student = $user?->studentProfile;

        $student?->loadMissing([
            'classroom.teacherSubjects.subject',
            'classroom.teacherSubjects.teacher',
            'attendances',
            'marks.exam.subject',
            'marks.exam.classroom',
            'feeInvoices',
        ]);

        // Real enrolled subjects from the student's classroom
        $enrolledSubjects = $student?->classroom?->teacherSubjects ?? collect();

        // Real recent marks
        $recentMarks = $student?->marks()
            ->with(['exam.subject', 'exam.classroom'])
            ->latest()
            ->get() ?? collect();

        // Calculate real GPA from marks if available
        $gpa = '0.00';
        if ($recentMarks->isNotEmpty()) {
            $points = $recentMarks->map(function (Mark $mark) {
                $score = (float) $mark->marks_obtained;

                return match (true) {
                    $score >= 90 => 4.00,
                    $score >= 85 => 3.50,
                    $score >= 80 => 3.00,
                    $score >= 75 => 2.50,
                    $score >= 65 => 2.00,
                    $score >= 50 => 1.00,
                    default => 0.00,
                };
            });
            $gpa = number_format((float) $points->avg(), 2);
        }

        // Real attendance stats
        $attendances = $student?->attendances ?? collect();
        $totalSessions = $attendances->count();
        $presentCount = $attendances->filter(fn ($a) => strtolower((string) $a->status) === 'present')->count();
        $lateCount = $attendances->filter(fn ($a) => strtolower((string) $a->status) === 'late')->count();
        $absentCount = $attendances->filter(fn ($a) => in_array(strtolower((string) $a->status), ['absent', 'excused'], true))->count();
        $attendanceRate = $totalSessions > 0 ? round(($presentCount / $totalSessions) * 100, 1).'%' : '0%';

        $attendanceStats = [
            'total' => $totalSessions,
            'present' => $presentCount,
            'late' => $lateCount,
            'absent' => $absentCount,
            'rate' => $attendanceRate,
        ];

        // Latest fee invoice
        $latestInvoice = $student?->feeInvoices()->latest()->first();

        return view('student.dashboard', [
            'student' => $student,
            'enrolledSubjects' => $enrolledSubjects,
            'recentMarks' => $recentMarks,
            'gpa' => $gpa,
            'attendanceRate' => $attendanceRate,
            'attendanceStats' => $attendanceStats,
            'latestInvoice' => $latestInvoice,
        ]);
    }

    /**
     * Display the student profile view.
     */
    public function profile(Request $request): View
    {
        $user = $request->user();
        $student = $user?->studentProfile;

        $student?->loadMissing([
            'user',
            'classroom.teacherSubjects.subject',
            'classroom.teacherSubjects.teacher',
            'attendances',
            'marks.exam.subject',
            'feeInvoices',
        ]);

        return view('student.profile.index', [
            'student' => $student,
            'user' => $user,
        ]);
    }

    /**
     * Update the student's profile information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $student = $request->user()?->studentProfile;

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
    }
}
