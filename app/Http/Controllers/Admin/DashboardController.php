<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\FeeInvoice;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalStudents = StudentProfile::count();
        $totalTeachers = TeacherProfile::count();
        $totalClassrooms = Classroom::count();

        // Attendance rate calculation
        $totalAttendanceRecords = Attendance::count();
        $presentAttendanceRecords = Attendance::where('status', 'present')->count();
        $attendanceRate = $totalAttendanceRecords > 0
            ? round(($presentAttendanceRecords / $totalAttendanceRecords) * 100, 1)
            : 96.8;

        // Recent student registrations
        $recentStudents = StudentProfile::with(['user', 'classroom'])
            ->latest()
            ->take(5)
            ->get();

        // Term fee collection metrics
        $totalFeesAmount = (float) FeeInvoice::sum('amount');
        $collectedFeesAmount = (float) FeeInvoice::where('status', 'paid')->sum('amount');
        $paidInvoicesCount = FeeInvoice::where('status', 'paid')->count();
        $unpaidInvoicesCount = FeeInvoice::where('status', '!=', 'paid')->count();
        $feeCollectionPercentage = $totalFeesAmount > 0
            ? round(($collectedFeesAmount / $totalFeesAmount) * 100, 1)
            : 0;

        // Upcoming examinations
        $upcomingExams = Exam::with(['subject', 'classroom'])
            ->orderBy('exam_date', 'asc')
            ->take(3)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalStudents',
            'totalTeachers',
            'totalClassrooms',
            'attendanceRate',
            'recentStudents',
            'totalFeesAmount',
            'collectedFeesAmount',
            'paidInvoicesCount',
            'unpaidInvoicesCount',
            'feeCollectionPercentage',
            'upcomingExams'
        ));
    }
}
