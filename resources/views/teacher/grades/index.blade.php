@extends('layouts.teacher')

@section('title', 'Exam Grades & Marks Entry')
@section('page_title', 'Grades')

@section('content')
<div class="space-y-6" x-data="{
    selectedExam: '{{ $selectedExamId ?? '' }}',
    selectedExamTitle: '{{ addslashes($selectedExamTitle ?? '') }}',
    selectedClass: '{{ $selectedClassroomId ?? '' }}',
    students: {{ Js::from($studentsPayload) }},
    calculateGrade(score) {
        if (score === null || score === '' || isNaN(parseFloat(score))) return '-';
        let s = parseFloat(score);
        if (s >= 90) return 'A';
        if (s >= 85) return 'B+';
        if (s >= 80) return 'B';
        if (s >= 70) return 'C+';
        if (s >= 65) return 'C';
        if (s >= 50) return 'D';
        return 'F';
    },
    gradeColor(score) {
        let g = this.calculateGrade(score);
        if (g === 'A') return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        if (g === 'B+' || g === 'B') return 'bg-sky-50 text-sky-700 border-sky-200';
        if (g === 'C+' || g === 'C') return 'bg-indigo-50 text-indigo-700 border-indigo-200';
        if (g === 'D') return 'bg-amber-50 text-amber-700 border-amber-200';
        if (g === '-') return 'bg-slate-50 text-slate-500 border-slate-200';
        return 'bg-rose-50 text-rose-700 border-rose-200';
    },
    classAverage() {
        if (!this.students || this.students.length === 0) return '0.0';
        let graded = this.students.filter(s => s.marks !== null && s.marks !== '' && !isNaN(parseFloat(s.marks)));
        if (graded.length === 0) return '0.0';
        let total = graded.reduce((acc, s) => acc + parseFloat(s.marks), 0);
        return (total / graded.length).toFixed(1);
    },
    onExamTitleChange() {
        const url = new URL(window.location.href);
        if (this.selectedClass) {
            url.searchParams.set('classroom_id', this.selectedClass);
        }
        if (this.selectedExamTitle) {
            url.searchParams.set('exam_title', this.selectedExamTitle);
            url.searchParams.delete('exam_id');
        }
        window.location.href = url.toString();
    },
    onFilterChange() {
        const url = new URL(window.location.href);
        if (this.selectedClass) {
            url.searchParams.set('classroom_id', this.selectedClass);
        } else {
            url.searchParams.delete('classroom_id');
        }
        if (this.selectedExamTitle) {
            url.searchParams.set('exam_title', this.selectedExamTitle);
        }
        if (this.selectedExam) {
            url.searchParams.set('exam_id', this.selectedExam);
        } else {
            url.searchParams.delete('exam_id');
        }
        window.location.href = url.toString();
    }
}">
    {{-- Success Notification --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 shadow-xs">
            <div class="p-2 bg-emerald-100 rounded-xl text-emerald-700">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <p class="font-semibold text-sm">{{ session('success') }}</p>
                <p class="text-xs text-emerald-600 mt-0.5">The marks have been recorded to the database and are now visible on student transcripts.</p>
            </div>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-sm shadow-xs">
            <p class="font-semibold mb-1">Please correct the following errors:</p>
            <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header Toolbar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl font-bold tracking-tight text-slate-900">Input Exam Marks</h1>
                @if(isset($selectedExamTitle) && $selectedExamTitle)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $selectedExamTitle }}
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-0.5">
                Record marks obtained for scheduled examinations. Letter grades calculate automatically.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- 1. Examination Title Picker (Shows each title only ONCE) -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Examination</label>
                <select 
                    x-model="selectedExamTitle" 
                    @change="onExamTitleChange()"
                    class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 font-semibold focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
                >
                    @forelse($examTitles as $title)
                        <option value="{{ $title }}" {{ ($selectedExamTitle ?? '') == $title ? 'selected' : '' }}>
                            {{ $title }} ({{ $groupedExams[$title]->count() }} {{ Str::plural('subject', $groupedExams[$title]->count()) }})
                        </option>
                    @empty
                        <option value="">No examinations</option>
                    @endforelse
                </select>
            </div>

            <!-- 2. Subject Picker under the chosen Examination Title -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Subject</label>
                <select 
                    x-model="selectedExam" 
                    @change="onFilterChange()"
                    class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 font-medium focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
                >
                    @forelse($titleSubjectExams as $subExam)
                        <option value="{{ $subExam->id }}" {{ ($selectedExamId ?? null) == $subExam->id ? 'selected' : '' }}>
                            {{ $subExam->subject?->name ?? 'Subject' }} ({{ $subExam->subject?->code ?? '' }})
                        </option>
                    @empty
                        <option value="">No subjects assigned</option>
                    @endforelse
                </select>
            </div>

            <!-- 3. Classroom Picker -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Classroom</label>
                <select 
                    x-model="selectedClass" 
                    @change="onFilterChange()"
                    class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 font-medium focus:ring-2 focus:ring-emerald-500/20 cursor-pointer"
                >
                    @forelse($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ ($selectedClassroomId ?? null) == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }} ({{ $classroom->studentProfiles?->count() ?? 0 }} students)
                        </option>
                    @empty
                        <option value="">No classrooms available</option>
                    @endforelse
                </select>
            </div>
        </div>
    </div>

    <!-- Subject Quick-Switch Navigation Tabs / Pills -->
    @if(isset($titleSubjectExams) && $titleSubjectExams->count() > 1)
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider shrink-0 pl-1">
                    {{ $selectedExamTitle }} Subjects:
                </span>
                @foreach($titleSubjectExams as $subExam)
                    <a 
                        href="{{ route('teacher.grades.index', ['classroom_id' => $selectedClassroomId, 'exam_title' => $selectedExamTitle, 'exam_id' => $subExam->id]) }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $selectedExamId == $subExam->id ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}"
                    >
                        <span>{{ $subExam->subject?->name ?? 'Subject' }}</span>
                        <span class="{{ $selectedExamId == $subExam->id ? 'text-indigo-200' : 'text-slate-400' }} font-mono text-[10px]">
                            ({{ $subExam->subject?->code ?? '' }})
                        </span>
                    </a>
                @endforeach
            </div>

            @if(isset($selectedExam))
                <div class="flex items-center gap-3 shrink-0 text-xs font-mono pr-1 text-slate-500">
                    <span>Date: <strong class="text-slate-700">{{ $selectedExam->exam_date ? $selectedExam->exam_date->format('M d, Y') : 'N/A' }}</strong></span>
                    <span>&bull;</span>
                    <span>Max: <strong class="text-slate-700">{{ number_format($selectedExam->total_marks, 2) }} Marks</strong></span>
                </div>
            @endif
        </div>
    @endif

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400">Class Average</p>
                <p class="text-2xl font-bold text-slate-900 mt-1 font-mono"><span x-text="classAverage()"></span> / 100</p>
            </div>
            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">Passing</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400">Total Enrolled</p>
                <p class="text-2xl font-bold text-slate-900 mt-1 font-mono" x-text="students ? students.length : 0"></p>
            </div>
            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">Database Roster</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400">Grading Scale</p>
                <p class="text-xs text-slate-500 mt-1">A: 90-100 &bull; B: 80-89 &bull; C: 65-79</p>
            </div>
            <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">Standard 4.0</span>
        </div>
    </div>

    <!-- Marks Entry Table Form -->
    @include('teacher.grades.table')

</div>
@endsection
