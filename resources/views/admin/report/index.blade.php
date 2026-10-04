@extends('layouts.admin')

@section('title', 'Student Scores & Academic Reports')
@section('page_title', 'Student Scores & Reports')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Student Scores & Examination Reports</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Live Teacher Submissions
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-1">
                Monitor student marks submitted by teachers, track academic performance across classrooms, and audit score sheets.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <button 
                type="button" 
                onclick="window.print()" 
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold shadow-2xs transition-colors cursor-pointer"
            >
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.07-.641-2.07-1.171-3.001M17.28 13.829c.24-1.07.641-2.07 1.171-3.001M9 17.25h6m-6 3h6m2.25-16.5H6.75A2.25 2.25 0 0 0 4.5 6v12a2.25 2.25 0 0 0 2.25 2.25h10.5A2.25 2.25 0 0 0 19.5 18V6a2.25 2.25 0 0 0-2.25-2.25Z" />
                </svg>
                <span>Print Score Sheet</span>
            </button>
        </div>
    </div>

    <!-- Analytics KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Average Score -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Average Score</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-2xl font-extrabold text-slate-900 font-mono">{{ $averageScore }}</span>
                    <span class="text-xs font-semibold text-slate-400">/ 100</span>
                </div>
                <span class="inline-flex items-center text-[11px] font-semibold text-indigo-600 mt-0.5">Overall Performance</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg shadow-2xs">
                📊
            </div>
        </div>

        <!-- Pass Rate -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pass Rate (&ge; 50)</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-2xl font-extrabold text-emerald-600 font-mono">{{ $passRate }}%</span>
                </div>
                <span class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $passedCount }} of {{ $totalMarksCount }} passed</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg shadow-2xs">
                🎓
            </div>
        </div>

        <!-- Highest Score -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Highest Score</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-2xl font-extrabold text-amber-600 font-mono">{{ number_format($highestScore, 2) }}</span>
                    <span class="text-xs font-semibold text-slate-400">Marks</span>
                </div>
                <span class="text-[11px] font-medium text-slate-400 mt-0.5">Top examination mark</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg shadow-2xs">
                🏆
            </div>
        </div>

        <!-- Total Submissions -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Graded Marks</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-2xl font-extrabold text-slate-900 font-mono">{{ $totalMarksCount }}</span>
                </div>
                <span class="text-[11px] font-medium text-slate-400 mt-0.5">Recorded in database</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-lg shadow-2xs">
                📝
            </div>
        </div>
    </div>

    <!-- Filters & Student Score Records Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <!-- Filter Toolbar -->
        <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/50">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-center gap-3">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px]">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search student name, code, exam or subject..."
                        class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                </div>

                <!-- Classroom Filter -->
                <div>
                    <select 
                        name="classroom_id" 
                        onchange="this.form.submit()"
                        class="py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 font-medium focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
                    >
                        <option value="all">All Classrooms</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Exam Title Filter -->
                <div>
                    <select 
                        name="exam_title" 
                        onchange="this.form.submit()"
                        class="py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 font-medium focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
                    >
                        <option value="all">All Examinations</option>
                        @foreach($examTitles as $title)
                            <option value="{{ $title }}" {{ request('exam_title') == $title ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Subject Filter -->
                <div>
                    <select 
                        name="subject_id" 
                        onchange="this.form.submit()"
                        class="py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 font-medium focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
                    >
                        <option value="all">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} ({{ $subject->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Grade / Status Filter -->
                <div>
                    <select 
                        name="status" 
                        onchange="this.form.submit()"
                        class="py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 font-medium focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
                    >
                        <option value="">All Grades & Status</option>
                        <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }}>Passed (&ge; 50)</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Needs Attention (&lt; 50)</option>
                        <option value="A" {{ request('status') === 'A' ? 'selected' : '' }}>Grade A (90-100)</option>
                        <option value="B+" {{ request('status') === 'B+' ? 'selected' : '' }}>Grade B+ (85-89)</option>
                        <option value="B" {{ request('status') === 'B' ? 'selected' : '' }}>Grade B (80-84)</option>
                        <option value="C+" {{ request('status') === 'C+' ? 'selected' : '' }}>Grade C+ (70-79)</option>
                        <option value="C" {{ request('status') === 'C' ? 'selected' : '' }}>Grade C (65-69)</option>
                        <option value="D" {{ request('status') === 'D' ? 'selected' : '' }}>Grade D (50-64)</option>
                        <option value="F" {{ request('status') === 'F' ? 'selected' : '' }}>Grade F (&lt; 50)</option>
                    </select>
                </div>

                <button 
                    type="submit" 
                    class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-xs font-semibold text-white transition-colors cursor-pointer"
                >
                    Filter
                </button>

                @if(request()->anyFilled(['search', 'classroom_id', 'exam_title', 'subject_id', 'status']))
                    <a 
                        href="{{ route('admin.reports.index') }}" 
                        class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline px-2"
                    >
                        Clear Filters
                    </a>
                @endif
            </form>
        </div>

        <!-- Scores Data Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <th scope="col" class="py-3.5 pl-6 pr-3">Student</th>
                        <th scope="col" class="py-3.5 px-3">Classroom</th>
                        <th scope="col" class="py-3.5 px-3">Examination & Subject</th>
                        <th scope="col" class="py-3.5 px-3">Exam Date</th>
                        <th scope="col" class="py-3.5 px-3">Score (Max 100)</th>
                        <th scope="col" class="py-3.5 px-3 text-center">Letter Grade</th>
                        <th scope="col" class="py-3.5 px-3">Status</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($marks as $record)
                        @php
                            $score = (float) $record->marks_obtained;
                            $grade = $record->grade_letter ?? '-';
                            $gradeColor = match($grade) {
                                'A' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'B+', 'B' => 'bg-sky-50 text-sky-700 border-sky-200',
                                'C+', 'C' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'D' => 'bg-amber-50 text-amber-700 border-amber-200',
                                default => 'bg-rose-50 text-rose-700 border-rose-200',
                            };
                            $studentName = $record->student?->user?->name ?? 'Student #'.$record->student_id;
                            $studentCode = $record->student?->student_code ?? 'STU-'.$record->student_id;
                            $classroomName = $record->student?->classroom?->name ?? $record->exam?->classroom?->name ?? 'Classroom';
                            $examTitle = $record->exam?->title ?? 'Exam';
                            $subjectName = $record->exam?->subject?->name ?? 'Subject';
                            $subjectCode = $record->exam?->subject?->code ?? '';
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Student Info -->
                            <td class="py-3.5 pl-6 pr-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs uppercase shrink-0">
                                        {{ strtoupper(substr($studentName, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 leading-snug">{{ $studentName }}</p>
                                        <span class="font-mono text-[11px] text-indigo-600 font-semibold">{{ $studentCode }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Classroom -->
                            <td class="py-3.5 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-medium">
                                    {{ $classroomName }}
                                </span>
                            </td>

                            <!-- Examination & Subject -->
                            <td class="py-3.5 px-3">
                                <p class="font-bold text-slate-900 leading-snug text-xs">{{ $examTitle }}</p>
                                <p class="text-xs text-slate-600 mt-0.5">
                                    {{ $subjectName }}
                                    @if($subjectCode)
                                        <span class="text-slate-400 font-mono text-[11px]">({{ $subjectCode }})</span>
                                    @endif
                                </p>
                            </td>

                            <!-- Exam Date -->
                            <td class="py-3.5 px-3 font-mono text-xs text-slate-600">
                                {{ $record->exam?->exam_date ? $record->exam->exam_date->format('M d, Y') : ($record->created_at ? $record->created_at->format('M d, Y') : 'N/A') }}
                            </td>

                            <!-- Score Obtained -->
                            <td class="py-3.5 px-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-slate-900 text-sm">
                                        {{ number_format($score, 2) }}
                                    </span>
                                    <span class="text-[11px] text-slate-400">/ 100</span>
                                </div>
                                <div class="w-24 bg-slate-100 rounded-full h-1.5 mt-1 overflow-hidden">
                                    <div 
                                        class="h-1.5 rounded-full {{ $score >= 50 ? 'bg-emerald-500' : 'bg-rose-500' }}" 
                                        style="width: {{ min(100, max(0, $score)) }}%"
                                    ></div>
                                </div>
                            </td>

                            <!-- Letter Grade -->
                            <td class="py-3.5 px-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold font-mono border {{ $gradeColor }}">
                                    {{ $grade }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-3 text-xs">
                                @if($score >= 50)
                                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        Passed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 font-semibold text-rose-600">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                        Needs Attention
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 pl-3 pr-6 text-right">
                                <div class="inline-flex items-center gap-3">
                                    <a 
                                        href="{{ route('pdf.report-card', ['student_id' => $record->student_id, 'user_id' => $record->student?->user_id]) }}" 
                                        target="_blank"
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline"
                                        title="View Student Report Card"
                                    >
                                        <span>Report Card</span>
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center">
                                <div class="max-w-sm mx-auto space-y-2">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl">
                                        📋
                                    </div>
                                    <p class="font-bold text-slate-800 text-sm">No Student Marks Found</p>
                                    <p class="text-xs text-slate-500">
                                        @if(request()->anyFilled(['search', 'classroom_id', 'exam_title', 'subject_id', 'status']))
                                            No records match your selected filter criteria. Try adjusting your filters.
                                        @else
                                            Teachers have not published any examination marks yet. Once marks are submitted via the Teacher Portal, they will appear here automatically.
                                        @endif
                                    </p>
                                    @if(request()->anyFilled(['search', 'classroom_id', 'exam_title', 'subject_id', 'status']))
                                        <div class="pt-2">
                                            <a href="{{ route('admin.reports.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                                                Clear all filters &rarr;
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @include('share.pagination', ['paginator' => $marks])
    </div>
</div>
@endsection
