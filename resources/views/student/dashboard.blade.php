@extends('layouts.student')

@section('title', 'Student Academic Portal')
@section('page_title', 'My Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Student Hero Greeting Banner -->
    <div class="bg-gradient-to-r from-sky-600 via-indigo-600 to-indigo-800 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 text-white font-bold text-2xl flex items-center justify-center shadow-md">
                    {{ strtoupper(substr(auth()->user()->name ?? 'ST', 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight">{{ auth()->user()->name ?? 'Student' }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white border border-white/30">{{ $student?->classroom?->name ?? 'Unassigned Class' }}</span>
                    </div>
                    <p class="text-sky-100 text-xs sm:text-sm mt-1">Student ID: <span class="font-mono font-bold">{{ $student?->student_code ?? 'STU-0000' }}</span> &bull; Academic Year {{ $student?->classroom?->academic_year ?? date('Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a 
                    href="{{ url('/pdf/report-card') }}" 
                    target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-indigo-700 hover:bg-sky-50 text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all"
                >
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>Download Report Card</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Student Key Metrics: GPA, Attendance, Subjects -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- Cumulative GPA -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Current GPA</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-3xl font-bold text-slate-900 font-mono">{{ $gpa ?? '0.00' }}</span>
                    <span class="text-xs font-semibold text-slate-400">/ 4.00</span>
                </div>
                <span class="inline-flex items-center text-xs font-semibold text-emerald-600 mt-1">Official Standing</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-2xl font-bold">
                🏆
            </div>
        </div>

        <!-- Attendance Rate -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Attendance Rate</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-3xl font-bold text-emerald-600 font-mono">{{ $attendanceRate ?? '0%' }}</span>
                </div>
                <span class="text-xs text-slate-400 mt-1">{{ $attendanceStats['present'] ?? 0 }} of {{ $attendanceStats['total'] ?? 0 }} sessions present</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-bold">
                📅
            </div>
        </div>

        <!-- Enrolled Subjects -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Enrolled Subjects</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-3xl font-bold text-indigo-600 font-mono">{{ $enrolledSubjects->count() }}</span>
                    <span class="text-xs font-semibold text-slate-400">Subjects</span>
                </div>
                <span class="text-xs text-slate-400 mt-1">{{ $student?->classroom?->name ?? 'Classroom' }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl font-bold">
                📖
            </div>
        </div>
    </div>

    <!-- Main Content: Grades Cards & Weekly Class Schedule -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Exam Grade Cards (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Recent Exam Results</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Published grades for enrolled subjects</p>
                    </div>
                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">Academic Standing</span>
                </div>

                <div class="p-5 divide-y divide-slate-100">
                    @forelse($recentMarks as $mark)
                        @php
                            $score = (float) $mark->marks_obtained;
                            $color = match (true) {
                                $score >= 85 => 'emerald',
                                $score >= 70 => 'sky',
                                $score >= 50 => 'amber',
                                default => 'rose',
                            };
                            $code = $mark->exam?->subject?->code ?? ('SUB-' . ($mark->exam?->subject_id ?? 1));
                            $subjName = $mark->exam?->subject?->name ?? 'Course Subject';
                            $examName = $mark->exam?->title ?? 'Examination';
                            $teacherName = $mark->exam?->classroom?->teachers?->first()?->name ?? 'Course Instructor';
                        @endphp
                        <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($code, 0, 3)) }}
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 leading-snug">{{ $subjName }}</h3>
                                    <p class="text-xs text-slate-400">{{ $examName }} &bull; {{ $teacherName }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <p class="text-sm font-bold font-mono text-slate-900">{{ number_format($score, 1) }} <span class="text-xs text-slate-400 font-normal">/ 100</span></p>
                                    <p class="text-[11px] text-slate-400">Score</p>
                                </div>
                                <span class="w-10 h-8 rounded-lg bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-200 font-bold font-mono text-sm flex items-center justify-center">
                                    {{ $mark->grade_letter ?? ($score >= 90 ? 'A' : ($score >= 80 ? 'B' : ($score >= 70 ? 'C' : ($score >= 50 ? 'D' : 'F')))) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400">
                            <div class="w-10 h-10 mx-auto rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 mb-2">
                                📝
                            </div>
                            <p class="text-sm font-medium text-slate-600">No exam results recorded yet</p>
                            <p class="text-xs text-slate-400 mt-1">Your published examination scores and grades will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Attendance Calendar Breakdown -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                <h3 class="text-sm font-bold text-slate-900 mb-3">Attendance Breakdown</h3>
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <p class="text-xs text-emerald-600 font-medium">Present</p>
                        <p class="text-xl font-bold text-emerald-800 mt-1 font-mono">{{ $attendanceStats['present'] ?? 0 }} Days</p>
                    </div>
                    <div class="p-3 rounded-xl bg-amber-50/70 border border-amber-100">
                        <p class="text-xs text-amber-600 font-medium">Late</p>
                        <p class="text-xl font-bold text-amber-800 mt-1 font-mono">{{ $attendanceStats['late'] ?? 0 }} Days</p>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <p class="text-xs text-slate-500 font-medium">Absent / Excused</p>
                        <p class="text-xl font-bold text-slate-700 mt-1 font-mono">{{ $attendanceStats['absent'] ?? 0 }} Days</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Schedule & Invoices (1 Col) -->
        <div class="space-y-6">
            <!-- Weekly Schedule -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Enrolled Class Curriculum</h3>
                <div class="mt-4 space-y-3">
                    @forelse($enrolledSubjects as $assignment)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-[11px] font-mono font-semibold text-indigo-600">{{ $assignment->subject?->code ?? 'COURSE' }}</span>
                            <p class="text-xs font-semibold text-slate-900 mt-0.5">{{ $assignment->subject?->name ?? 'Course Subject' }}</p>
                            <p class="text-[11px] text-slate-400">Instructor: {{ $assignment->teacher?->name ?? 'Academic Faculty' }}</p>
                        </div>
                    @empty
                        <div class="py-4 text-center text-slate-400">
                            <p class="text-xs">No active subjects assigned to your classroom.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Fee Status Widget -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Tuition Fee Status</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ ($latestInvoice?->status === 'paid') ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ ucfirst($latestInvoice?->status ?? 'All Clear') }}
                    </span>
                </div>
                @if($latestInvoice)
                    <div class="mt-4 p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-800">{{ $latestInvoice->title ?? 'Tuition Fee' }}</p>
                            <p class="text-[11px] text-slate-400">{{ $latestInvoice->due_date ? 'Due ' . \Illuminate\Support\Carbon::parse($latestInvoice->due_date)->format('M d, Y') : 'Academic Invoice' }}</p>
                        </div>
                        <span class="font-mono text-xs font-bold text-slate-900">${{ number_format((float) ($latestInvoice->amount ?? 0), 2) }}</span>
                    </div>
                @else
                    <div class="mt-4 p-3 rounded-xl bg-slate-50 border border-slate-100 text-center text-xs text-slate-500">
                        No pending tuition invoices on file.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
