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
                href="{{ route('teacher.attendance.index') }}" 
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
            <p class="text-2xl font-bold text-slate-900 mt-1.5 font-mono">{{ $attendanceRate }}%</p>
            <p class="text-xs text-emerald-600 mt-1 font-medium">
                @if($totalForDate > 0)
                    Calculated for {{ \Carbon\Carbon::parse($filterDate)->format('M d, Y') }}
                @else
                    All-time overall rate
                @endif
            </p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Present</span>
            <p class="text-2xl font-bold text-emerald-700 mt-1.5 font-mono">{{ number_format($presentToday) }}</p>
            <p class="text-xs text-slate-400 mt-1">Across {{ $classroomsCount }} {{ Str::plural('classroom', $classroomsCount) }} recorded</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Unexcused Absences</span>
            <p class="text-2xl font-bold text-rose-700 mt-1.5 font-mono">{{ number_format($absentToday) }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $absentToday > 0 ? 'Requires attention / notification' : 'No unexcused absences' }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Late Arrivals</span>
            <p class="text-2xl font-bold text-amber-700 mt-1.5 font-mono">{{ number_format($lateToday) }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ number_format($excusedToday) }} excused entries</p>
        </div>
    </div>

    <!-- Attendance Log Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <!-- Filter Toolbar -->
        <form method="GET" action="{{ route('admin.attendances.index') }}" class="p-4 sm:px-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-slate-100">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Date Picker -->
                <div>
                    <input 
                        type="date" 
                        name="date"
                        value="{{ request('date', $filterDate) }}" 
                        onchange="this.form.submit()"
                        class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                </div>

                <!-- Classroom Filter -->
                <div>
                    <select 
                        name="classroom_id" 
                        onchange="this.form.submit()"
                        class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                        <option value="all">All Classrooms</option>
                        @foreach($classrooms as $c)
                            <option value="{{ $c->id }}" {{ request('classroom_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select 
                        name="status" 
                        onchange="this.form.submit()"
                        class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                        <option value="all">All Statuses</option>
                        <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Late</option>
                        <option value="excused" {{ request('status') === 'excused' ? 'selected' : '' }}>Excused</option>
                    </select>
                </div>

                <!-- Search Input -->
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search student or code..." 
                        class="w-48 sm:w-60 py-2 pl-8 pr-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>

                @if(request()->filled('date') || (request()->filled('classroom_id') && request('classroom_id') !== 'all') || (request()->filled('status') && request('status') !== 'all') || request()->filled('search'))
                    <a 
                        href="{{ route('admin.attendances.index') }}" 
                        class="py-2 px-3 text-xs font-semibold text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors"
                    >
                        Reset Filters
                    </a>
                @endif
            </div>

            <div class="flex items-center gap-2 text-xs text-slate-400">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Live records from database</span>
            </div>
        </form>

        <div class="overflow-x-auto">
            @include('admin.attendances.table')
        </div>

        <!-- Pagination -->
        @include('share.pagination', ['paginator' => $attendances])
    </div>
</div>
@endsection
