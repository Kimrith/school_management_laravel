<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Display school-wide attendance records and live statistics.
     */
    public function index(Request $request): View
    {
        $classrooms = Classroom::orderBy('name')->get();

        $query = Attendance::with(['student.user', 'classroom'])->latest('date')->latest('id');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('classroom_id') && $request->classroom_id !== 'all') {
            $query->where('classroom_id', $request->classroom_id);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where(function ($q) use ($search) {
                $q->whereHas('student.user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })->orWhereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where('student_code', 'like', "%{$search}%");
                });
            });
        }

        $attendances = $query->paginate(7)->withQueryString();

        // Calculate live statistics
        $filterDate = $request->input('date', now()->format('Y-m-d'));
        $dateScope = Attendance::whereDate('date', $filterDate);

        $totalForDate = (clone $dateScope)->count();
        $presentToday = (clone $dateScope)->where('status', 'present')->count();
        $absentToday = (clone $dateScope)->where('status', 'absent')->count();
        $lateToday = (clone $dateScope)->where('status', 'late')->count();
        $excusedToday = (clone $dateScope)->where('status', 'excused')->count();
        $classroomsCount = (clone $dateScope)->distinct('classroom_id')->count('classroom_id');

        // All-time attendance rate
        $totalAllTime = Attendance::count();
        $presentAllTime = Attendance::where('status', 'present')->count();
        $attendanceRate = $totalForDate > 0
            ? round(($presentToday / $totalForDate) * 100, 1)
            : ($totalAllTime > 0 ? round(($presentAllTime / $totalAllTime) * 100, 1) : 100.0);

        return view('admin.attendances.index', compact(
            'attendances',
            'classrooms',
            'filterDate',
            'attendanceRate',
            'totalForDate',
            'presentToday',
            'absentToday',
            'lateToday',
            'excusedToday',
            'classroomsCount'
        ));
    }
}
