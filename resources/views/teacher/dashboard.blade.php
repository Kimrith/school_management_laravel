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

@php
    $assignedClassrooms = $assignedClassrooms ?? (auth()->user()?->taughtClassrooms?->unique('id') ?? collect());
    $totalStudents = $totalStudents ?? ($assignedClassrooms->isNotEmpty() ? \App\Models\StudentProfile::whereIn('classroom_id', $assignedClassrooms->pluck('id'))->count() : 0);
    $teacherSubjects = $teacherSubjects ?? (auth()->user() ? \App\Models\TeacherSubject::with(['classroom.studentProfiles', 'subject'])->where('teacher_id', auth()->id())->get() : collect());
@endphp

    <!-- Teaching Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Assigned Classes</p>
                <p class="text-2xl font-bold text-slate-900 mt-1 font-mono">{{ $assignedClassrooms->count() }} {{ \Illuminate\Support\Str::plural('Class', $assignedClassrooms->count()) }}</p>
                <span class="text-xs text-emerald-600 mt-1 truncate block max-w-xs">{{ $assignedClassrooms->pluck('name')->join(', ') ?: 'No classrooms assigned' }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                🏫
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Students</p>
                <p class="text-2xl font-bold text-indigo-600 mt-1 font-mono">{{ $totalStudents }} Students</p>
                <span class="text-xs text-slate-400 mt-1">{{ $totalStudents > 0 ? 'Under direct instruction' : 'No students assigned yet' }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                👨‍🎓
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pending Grading</p>
                <p class="text-2xl font-bold text-amber-600 mt-1 font-mono">0 Assessments</p>
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
                @forelse($teacherSubjects as $assignment)
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-mono font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">Weekly Class</span>
                            <h3 class="text-sm font-bold text-slate-900 mt-1.5">{{ $assignment->subject?->name ?? 'Teaching Subject' }} ({{ $assignment->subject?->code ?? 'SUB' }})</h3>
                            <p class="text-xs text-slate-400">{{ $assignment->classroom?->name ?? 'Classroom' }} ({{ $assignment->classroom?->studentProfiles?->count() ?? 0 }} Students)</p>
                        </div>
                        <a href="{{ route('teacher.attendance.index', ['classroom_id' => $assignment->classroom_id]) }}" class="px-3 py-1.5 bg-white border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 rounded-lg shadow-2xs">Attendance</a>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400">
                        <div class="w-10 h-10 mx-auto rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 mb-2">
                            🏫
                        </div>
                        <p class="text-sm font-medium text-slate-600">No classes currently assigned</p>
                        <p class="text-xs text-slate-400 mt-0.5">When the administration assigns classrooms to your account, they will appear here.</p>
                    </div>
                @endforelse
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
