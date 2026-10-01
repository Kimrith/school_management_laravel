@extends('layouts.teacher')

@section('title', 'Faculty Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Faculty Hero Banner -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 text-white font-bold text-2xl flex items-center justify-center shadow-md">
                    {{ strtoupper(substr(auth()->user()->name ?? 'Prof', 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Welcome, {{ auth()->user()->name ?? 'Prof. Virak Meas' }}</h1>
                    <p class="text-emerald-100 text-xs sm:text-sm mt-1">Computer Science & Web Engineering &bull; Master of Computer Science</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a 
                    href="{{ url('/teacher/attendance') }}" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-emerald-800 hover:bg-emerald-50 text-xs sm:text-sm font-bold shadow-md transition-all"
                >
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>Mark Today's Attendance</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Teaching Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Assigned Classes</p>
                <p class="text-2xl font-bold text-slate-900 mt-1 font-mono">2 Classes</p>
                <span class="text-xs text-emerald-600 mt-1">Grade 10-A, Grade 12-A</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                🏫
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Students</p>
                <p class="text-2xl font-bold text-indigo-600 mt-1 font-mono">78 Students</p>
                <span class="text-xs text-slate-400 mt-1">Under direct instruction</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                👨‍🎓
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pending Grading</p>
                <p class="text-2xl font-bold text-amber-600 mt-1 font-mono">1 Assessment</p>
                <a href="{{ url('/teacher/grades') }}" class="text-xs text-indigo-600 font-semibold hover:underline mt-1">Grade now &rarr;</a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                📝
            </div>
        </div>
    </div>

    <!-- Teacher Schedule & Fast Links -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
            <h2 class="text-base font-bold text-slate-900 mb-4">My Weekly Teaching Timetable</h2>
            <div class="space-y-3">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-mono font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">Mon &bull; 08:00 AM - 09:30 AM</span>
                        <h3 class="text-sm font-bold text-slate-900 mt-1.5">Web Application Development (WEB401)</h3>
                        <p class="text-xs text-slate-400">Room 302 &bull; Grade 10-A (38 Students)</p>
                    </div>
                    <a href="{{ url('/teacher/attendance') }}" class="px-3 py-1.5 bg-white border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 rounded-lg shadow-2xs">Attendance</a>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-mono font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">Wed &bull; 10:00 AM - 11:30 AM</span>
                        <h3 class="text-sm font-bold text-slate-900 mt-1.5">Relational Database Systems (DBS301)</h3>
                        <p class="text-xs text-slate-400">Room 402 &bull; Grade 12-A (40 Students)</p>
                    </div>
                    <a href="{{ url('/teacher/attendance') }}" class="px-3 py-1.5 bg-white border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 rounded-lg shadow-2xs">Attendance</a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
            <h2 class="text-base font-bold text-slate-900">Faculty Shortcuts</h2>
            <div class="space-y-2.5">
                <a href="{{ url('/teacher/attendance') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-emerald-50/70 border border-slate-100 text-xs font-semibold text-slate-800 transition-colors">
                    <span>Mark Student Attendance</span>
                    <span class="text-emerald-600">&rarr;</span>
                </a>
                <a href="{{ url('/teacher/grades') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-emerald-50/70 border border-slate-100 text-xs font-semibold text-slate-800 transition-colors">
                    <span>Submit Exam Grades</span>
                    <span class="text-emerald-600">&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
