@extends('layouts.admin')

@section('title', 'Attendance Management')
@section('page_title', 'Attendances')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Attendance Monitoring</h1>
            <p class="text-sm text-slate-500 mt-1">School-wide daily student attendance tracking and absentees management.</p>
        </div>
        <div class="flex items-center gap-3">
            <a 
                href="{{ url('/teacher/attendance') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition-all"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Take Attendance</span>
            </a>
        </div>
    </div>

    <!-- Attendance Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Attendance Rate</span>
            <p class="text-2xl font-bold text-slate-900 mt-1.5 font-mono">96.8%</p>
            <p class="text-xs text-emerald-600 mt-1 font-medium">&uarr; +1.4% vs last week</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Present Today</span>
            <p class="text-2xl font-bold text-emerald-700 mt-1.5 font-mono">1,243</p>
            <p class="text-xs text-slate-400 mt-1">Across 34 classrooms</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Unexcused Absences</span>
            <p class="text-2xl font-bold text-rose-700 mt-1.5 font-mono">28</p>
            <p class="text-xs text-slate-400 mt-1">Parents notified via SMS</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Late Arrivals</span>
            <p class="text-2xl font-bold text-amber-700 mt-1.5 font-mono">13</p>
            <p class="text-xs text-slate-400 mt-1">Logged before 08:30 AM</p>
        </div>
    </div>

    <!-- Attendance Log Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <!-- Filter Toolbar -->
        <div class="p-4 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100">
            <div class="flex flex-wrap items-center gap-3">
                <input 
                    type="date" 
                    value="{{ date('Y-m-d') }}" 
                    class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 font-medium"
                >
                <select class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700">
                    <option value="">All Classrooms</option>
                    <option value="10-A">Grade 10-A</option>
                    <option value="11-B">Grade 11-B</option>
                    <option value="12-A">Grade 12-A</option>
                </select>
                <select class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700">
                    <option value="">All Statuses</option>
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="late">Late</option>
                    <option value="excused">Excused</option>
                </select>
            </div>
            <p class="text-xs text-slate-400">Live system records</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <th scope="col" class="py-3.5 pl-6 pr-3">Student Name</th>
                        <th scope="col" class="py-3.5 px-3">Student Code</th>
                        <th scope="col" class="py-3.5 px-3">Classroom</th>
                        <th scope="col" class="py-3.5 px-3">Date</th>
                        <th scope="col" class="py-3.5 px-3">Status</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @php
                        $attendanceRecords = [
                            ['name' => 'Sokha Chan', 'code' => 'STU-1001', 'class' => 'Grade 10-A', 'date' => date('M d, Y'), 'status' => 'present', 'remarks' => 'On time'],
                            ['name' => 'Bopha Vong', 'code' => 'STU-1002', 'class' => 'Grade 10-A', 'date' => date('M d, Y'), 'status' => 'present', 'remarks' => 'On time'],
                            ['name' => 'Dara Rath', 'code' => 'STU-1003', 'class' => 'Grade 11-B', 'date' => date('M d, Y'), 'status' => 'absent', 'remarks' => 'Parent informed - flu'],
                            ['name' => 'Chanthou Seng', 'code' => 'STU-1004', 'class' => 'Grade 12-A', 'date' => date('M d, Y'), 'status' => 'present', 'remarks' => 'On time'],
                            ['name' => 'Panha Lim', 'code' => 'STU-1005', 'class' => 'Grade 9-C', 'date' => date('M d, Y'), 'status' => 'late', 'remarks' => 'Arrived 08:15 AM'],
                            ['name' => 'Monyroth Keo', 'code' => 'STU-1006', 'class' => 'Grade 10-A', 'date' => date('M d, Y'), 'status' => 'excused', 'remarks' => 'Approved sports leave'],
                        ];
                    @endphp

                    @foreach($attendanceRecords as $row)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 pl-6 pr-3 font-semibold text-slate-900">{{ $row['name'] }}</td>
                            <td class="py-3.5 px-3 font-mono text-xs text-indigo-600">{{ $row['code'] }}</td>
                            <td class="py-3.5 px-3 text-xs text-slate-700">{{ $row['class'] }}</td>
                            <td class="py-3.5 px-3 text-xs text-slate-500 font-mono">{{ $row['date'] }}</td>
                            <td class="py-3.5 px-3">
                                @if($row['status'] === 'present')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Present
                                    </span>
                                @elseif($row['status'] === 'absent')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Absent
                                    </span>
                                @elseif($row['status'] === 'late')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Late
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-50 text-sky-700 border border-sky-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                        Excused
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 pl-3 pr-6 text-right text-xs text-slate-400">
                                {{ $row['remarks'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
