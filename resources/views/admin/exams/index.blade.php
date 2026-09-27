@extends('layouts.admin')

@section('title', 'Examinations & Marks')
@section('page_title', 'Exams & Marks')

@section('content')
<div class="space-y-6" x-data="{ addExamModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Examinations & Assessments</h1>
            <p class="text-sm text-slate-500 mt-1">Schedule tests, assign total marks, and oversee academic grade records.</p>
        </div>
        <div class="flex items-center gap-3">
            <button 
                @click="addExamModal = true"
                type="button" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm hover:shadow-md transition-all cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Schedule Exam</span>
            </button>
        </div>
    </div>

    <!-- Exams Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <input 
                    type="text" 
                    placeholder="Search exam title..." 
                    class="w-full sm:w-64 pl-3.5 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none"
                >
                <select class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700">
                    <option value="">All Classrooms</option>
                    <option value="10-A">Grade 10-A</option>
                    <option value="11-B">Grade 11-B</option>
                    <option value="12-A">Grade 12-A</option>
                </select>
            </div>
            <p class="text-xs text-slate-400">Current Semester Exams</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <th scope="col" class="py-3.5 pl-6 pr-3">Exam Title</th>
                        <th scope="col" class="py-3.5 px-3">Subject</th>
                        <th scope="col" class="py-3.5 px-3">Classroom</th>
                        <th scope="col" class="py-3.5 px-3">Exam Date</th>
                        <th scope="col" class="py-3.5 px-3">Total Marks</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @php
                        $exams = [
                            ['title' => 'Web Development Midterm Examination', 'subject' => 'Web Development (WEB401)', 'class' => 'Grade 10-A', 'date' => 'Oct 15, 2026', 'marks' => '100.00', 'status' => 'scheduled'],
                            ['title' => 'Advanced Calculus Midterm Assessment', 'subject' => 'Mathematics & Logic (MATH101)', 'class' => 'Grade 11-B', 'date' => 'Oct 22, 2026', 'marks' => '100.00', 'status' => 'scheduled'],
                            ['title' => 'Database SQL Practical Exam', 'subject' => 'Database Systems (DBS301)', 'class' => 'Grade 12-A', 'date' => 'Nov 05, 2026', 'marks' => '100.00', 'status' => 'upcoming'],
                            ['title' => 'Technical English Communications Presentation', 'subject' => 'English Communications (ENG201)', 'class' => 'Grade 10-A', 'date' => 'Nov 12, 2026', 'marks' => '50.00', 'status' => 'upcoming'],
                        ];
                    @endphp

                    @foreach($exams as $exam)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 pl-6 pr-3">
                                <p class="font-semibold text-slate-900 leading-snug">{{ $exam['title'] }}</p>
                                <span class="inline-flex items-center gap-1.5 text-xs text-indigo-600 font-medium">Standard Written Exam</span>
                            </td>
                            <td class="py-4 px-3 text-xs text-slate-700 font-medium">
                                {{ $exam['subject'] }}
                            </td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-medium">
                                    {{ $exam['class'] }}
                                </span>
                            </td>
                            <td class="py-4 px-3 font-mono text-xs text-slate-600">
                                {{ $exam['date'] }}
                            </td>
                            <td class="py-4 px-3 font-mono font-semibold text-slate-900 text-xs">
                                {{ $exam['marks'] }} Marks
                            </td>
                            <td class="py-4 pl-3 pr-6 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a 
                                        href="{{ url('/teacher/grades') }}" 
                                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline"
                                    >
                                        Marks Sheet &rarr;
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Schedule Exam Modal -->
    <div x-show="addExamModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            <div x-show="addExamModal" @click="addExamModal = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs"></div>
            <div x-show="addExamModal" class="relative bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-xl border border-slate-200/80 my-8 z-10">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Schedule Examination</h3>
                    <button @click="addExamModal = false" class="p-1 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="#" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Exam Title</label>
                        <input type="text" placeholder="e.g. Midterm Examination" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Subject</label>
                        <select class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                            <option>Web Application Development (WEB401)</option>
                            <option>Discrete Mathematics & Logic (MATH101)</option>
                            <option>Relational Database Systems (DBS301)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Classroom</label>
                        <select class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                            <option>Grade 10-A</option>
                            <option>Grade 11-B</option>
                            <option>Grade 12-A</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Exam Date</label>
                            <input type="date" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Total Marks</label>
                            <input type="number" value="100.00" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none font-mono">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="addExamModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 rounded-xl border border-slate-200">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm">Schedule Exam</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
