@extends('layouts.admin')

@section('title', 'Curriculum & Subjects')
@section('page_title', 'Subjects')

@section('content')
<div class="space-y-6" x-data="{ addSubjectModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Academic Subjects</h1>
            <p class="text-sm text-slate-500 mt-1">Manage school curriculum, courses, syllabi, and teacher assignments.</p>
        </div>
        <div class="flex items-center gap-3">
            <button 
                @click="addSubjectModal = true"
                type="button" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm hover:shadow-md transition-all cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Subject</span>
            </button>
        </div>
    </div>

    <!-- Subjects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @php
            $subjectsList = [
                ['name' => 'Web Application Development', 'code' => 'WEB401', 'desc' => 'Fullstack development with Laravel, Eloquent ORM, Blade, and modern frontend styling.', 'teachers' => ['Prof. Virak Meas'], 'classes' => ['Grade 10-A', 'Grade 12-A']],
                ['name' => 'Relational Database Management', 'code' => 'DBS301', 'desc' => 'Relational schema modeling, normalization, SQL queries, indexing, and transactions.', 'teachers' => ['Prof. Virak Meas'], 'classes' => ['Grade 11-B', 'Grade 12-A']],
                ['name' => 'Discrete Mathematics & Logic', 'code' => 'MATH101', 'desc' => 'Set theory, propositional logic, graph theory, induction, and combinatorial analysis.', 'teachers' => ['Dr. Sopheap Ouk'], 'classes' => ['Grade 10-A', 'Grade 11-A']],
                ['name' => 'Technical English Communications', 'code' => 'ENG201', 'desc' => 'Professional technical writing, academic presentation, and international business communication.', 'teachers' => ['Ms. Kunthea Chea'], 'classes' => ['Grade 10-A', 'Grade 9-C']],
                ['name' => 'Data Communication & Networks', 'code' => 'NET202', 'desc' => 'OSI model, TCP/IP networking, routing protocols, subnetting, and network security.', 'teachers' => ['Mr. Samnang Heng'], 'classes' => ['Grade 11-B', 'Grade 12-A']],
                ['name' => 'Object-Oriented Programming (OOP)', 'code' => 'PRG102', 'desc' => 'Classes, inheritance, polymorphism, encapsulation, and design patterns in modern software.', 'teachers' => ['Prof. Virak Meas'], 'classes' => ['Grade 10-A', 'Grade 11-B']],
            ];
        @endphp

        @foreach($subjectsList as $subj)
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:border-slate-300 hover:shadow-sm transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-mono font-bold border border-indigo-100">
                            {{ $subj['code'] }}
                        </span>
                        <span class="text-xs text-slate-400 font-medium">Core Subject</span>
                    </div>

                    <h2 class="text-base font-bold text-slate-900 mt-3 leading-snug">{{ $subj['name'] }}</h2>
                    <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">{{ $subj['desc'] }}</p>

                    <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5 text-xs">
                        <p class="text-slate-400">Assigned Faculty: <span class="text-slate-800 font-semibold">{{ implode(', ', $subj['teachers']) }}</span></p>
                        <p class="text-slate-400">Classrooms: <span class="text-indigo-600 font-medium">{{ implode(', ', $subj['classes']) }}</span></p>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-end gap-2 text-xs">
                    <button type="button" class="text-indigo-600 hover:text-indigo-800 font-semibold">Edit Subject</button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add Subject Modal -->
    <div x-show="addSubjectModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            <div x-show="addSubjectModal" @click="addSubjectModal = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs"></div>
            <div x-show="addSubjectModal" class="relative bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-xl border border-slate-200/80 my-8 z-10">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Add New Subject</h3>
                    <button @click="addSubjectModal = false" class="p-1 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="#" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Subject Name</label>
                        <input type="text" placeholder="e.g. Cloud Computing" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Subject Code</label>
                        <input type="text" placeholder="e.g. CLD401" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Description</label>
                        <textarea rows="3" placeholder="Brief course syllabus summary..." class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none"></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="addSubjectModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 rounded-xl border border-slate-200">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm">Save Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
