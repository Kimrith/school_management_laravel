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
                    {{ strtoupper(substr(auth()->user()->name ?? 'Sokha Chan', 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight">{{ auth()->user()->name ?? 'Sokha Chan' }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white border border-white/30">Grade 10-A</span>
                    </div>
                    <p class="text-sky-100 text-xs sm:text-sm mt-1">Student ID: <span class="font-mono font-bold">STU-1001</span> &bull; Academic Year 2025-2026</p>
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
                    <span class="text-3xl font-bold text-slate-900 font-mono">3.85</span>
                    <span class="text-xs font-semibold text-slate-400">/ 4.00</span>
                </div>
                <span class="inline-flex items-center text-xs font-semibold text-emerald-600 mt-1">Grade Rank: Top 5%</span>
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
                    <span class="text-3xl font-bold text-emerald-600 font-mono">98.2%</span>
                </div>
                <span class="text-xs text-slate-400 mt-1">54 of 55 sessions present</span>
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
                    <span class="text-3xl font-bold text-indigo-600 font-mono">6</span>
                    <span class="text-xs font-semibold text-slate-400">Subjects</span>
                </div>
                <span class="text-xs text-slate-400 mt-1">All terms active</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl font-bold">
                📖
            </div>
        </div>
    </div>

<!-- Filter & Sort Toolbar: Exam Selection, Subject Search, and Score Sorting -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
        
        <!-- 1. Select Exam Query Dropdown -->
        <div>
            <label for="exam-select" class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Select Exam</label>
            <div class="relative">
                <select 
                    id="exam-select" 
                    name="exam_filter" 
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs sm:text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 font-medium transition-all appearance-none pr-10"
                >
                    <option value="">All Examinations</option>
                    <option value="midterm">Midterm Exam (Web Dev)</option>
                    <option value="final">Final Term Exam (Algorithms)</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 2. Subject Name Search Input -->
        <div>
            <label for="subject-search" class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Search Subject</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    id="subject-search" 
                    placeholder="e.g. Web Development..." 
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs sm:text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block pl-9 pr-3 py-2.5 font-medium transition-all placeholder:text-slate-400"
                >
            </div>
        </div>

        <!-- 3. Sort Score Dropdown (High to Low / Low to High) -->
        <div>
            <label for="score-sort" class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Sort Score</label>
            <div class="relative">
                <select 
                    id="score-sort" 
                    name="sort_score" 
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs sm:text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 font-medium transition-all appearance-none pr-10"
                >
                    <option value="">Default Order</option>
                    <option value="high-to-low">High to Low Score</option>
                    <option value="low-to-high">Low to High Score</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                    </svg>
                </div>
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
                        <p class="text-xs text-slate-400 mt-0.5">Published grades for current term examinations</p>
                    </div>
                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">Term 1 Results</span>
                </div>

                <div class="p-5 divide-y divide-slate-100">
                    @php
                        $myGrades = [
                            ['subject' => 'Web Development', 'code' => 'WEB401', 'teacher' => 'Prof. Virak Meas', 'exam' => 'Midterm Examination', 'score' => 95.5, 'grade' => 'A', 'color' => 'emerald'],
                            ['subject' => 'Mathematics & Logic', 'code' => 'MATH101', 'teacher' => 'Dr. Sopheap Ouk', 'exam' => 'Midterm Examination', 'score' => 88.0, 'grade' => 'B+', 'color' => 'sky'],
                            ['subject' => 'English Communications', 'code' => 'ENG201', 'teacher' => 'Ms. Kunthea Chea', 'exam' => 'Midterm Oral & Written', 'score' => 92.0, 'grade' => 'A', 'color' => 'emerald'],
                            ['subject' => 'Database Systems', 'code' => 'DBS301', 'teacher' => 'Prof. Virak Meas', 'exam' => 'SQL Practical Test', 'score' => 84.5, 'grade' => 'B', 'color' => 'sky'],
                        ];
                    @endphp

                    @foreach($myGrades as $grade)
                        <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ substr($grade['code'], 0, 3) }}
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 leading-snug">{{ $grade['subject'] }}</h3>
                                    <p class="text-xs text-slate-400">{{ $grade['exam'] }} &bull; {{ $grade['teacher'] }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <p class="text-sm font-bold font-mono text-slate-900">{{ $grade['score'] }} <span class="text-xs text-slate-400 font-normal">/ 100</span></p>
                                    <p class="text-[11px] text-slate-400">Score</p>
                                </div>
                                <span class="w-10 h-8 rounded-lg bg-{{ $grade['color'] }}-50 text-{{ $grade['color'] }}-700 border border-{{ $grade['color'] }}-200 font-bold font-mono text-sm flex items-center justify-center">
                                    {{ $grade['grade'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Attendance Calendar Breakdown -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                <h3 class="text-sm font-bold text-slate-900 mb-3">Attendance Breakdown</h3>
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <p class="text-xs text-emerald-600 font-medium">Present</p>
                        <p class="text-xl font-bold text-emerald-800 mt-1 font-mono">54 Days</p>
                    </div>
                    <div class="p-3 rounded-xl bg-amber-50/70 border border-amber-100">
                        <p class="text-xs text-amber-600 font-medium">Late</p>
                        <p class="text-xl font-bold text-amber-800 mt-1 font-mono">1 Day</p>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <p class="text-xs text-slate-500 font-medium">Absent</p>
                        <p class="text-xl font-bold text-slate-700 mt-1 font-mono">0 Days</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Schedule & Invoices (1 Col) -->
        <div class="space-y-6">
            <!-- Weekly Schedule -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Today's Class Schedule</h3>
                <div class="mt-4 space-y-3">
                    <div class="p-3 rounded-xl bg-indigo-50/50 border border-indigo-100">
                        <span class="text-[11px] font-mono font-semibold text-indigo-600">08:00 AM - 09:30 AM</span>
                        <p class="text-xs font-semibold text-slate-900 mt-0.5">Web Development</p>
                        <p class="text-[11px] text-slate-400">Room 302 &bull; Prof. Virak Meas</p>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] font-mono font-semibold text-slate-500">10:00 AM - 11:30 AM</span>
                        <p class="text-xs font-semibold text-slate-900 mt-0.5">Mathematics & Logic</p>
                        <p class="text-[11px] text-slate-400">Room 204 &bull; Dr. Sopheap Ouk</p>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] font-mono font-semibold text-slate-500">01:30 PM - 03:00 PM</span>
                        <p class="text-xs font-semibold text-slate-900 mt-0.5">English Communications</p>
                        <p class="text-[11px] text-slate-400">Room 105 &bull; Ms. Kunthea Chea</p>
                    </div>
                </div>
            </div>

            <!-- Fee Status Widget -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Tuition Fee Status</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">All Clear</span>
                </div>
                <div class="mt-4 p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-800">Term 1 Tuition</p>
                        <p class="text-[11px] text-slate-400">Paid Oct 02, 2026</p>
                    </div>
                    <span class="font-mono text-xs font-bold text-slate-900">$350.00</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
