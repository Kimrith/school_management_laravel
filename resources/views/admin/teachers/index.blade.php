@extends('layouts.admin')

@section('title', 'Faculty & Teachers')
@section('page_title', 'Teachers')

@section('content')
<div class="space-y-6" x-data="{ addTeacherModal: false }">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Faculty Members</h1>
            <p class="text-sm text-slate-500 mt-1">Manage teaching staff, departmental assignments, and subject allocation.</p>
        </div>
        <div class="flex items-center gap-3">
            <button 
                @click="addTeacherModal = true"
                type="button" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm hover:shadow-md transition-all duration-150 cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Teacher</span>
            </button>
        </div>
    </div>

    <!-- Filters & Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Teachers</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">86</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                👨‍🏫
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Assigned Classes</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">34</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                🏫
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Subjects</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">18</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold">
                📚
            </div>
        </div>
    </div>

    <!-- Teachers Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <!-- Table Toolbar -->
        <div class="p-4 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100">
            <div class="relative w-full sm:w-72">
                <input 
                    type="text" 
                    placeholder="Search by faculty name, subject..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                >
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <select class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700">
                    <option value="">All Specializations</option>
                    <option value="cs">Computer Science</option>
                    <option value="math">Mathematics</option>
                    <option value="science">Sciences</option>
                    <option value="english">Languages</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <th scope="col" class="py-3.5 pl-6 pr-3">Instructor</th>
                        <th scope="col" class="py-3.5 px-3">Qualification</th>
                        <th scope="col" class="py-3.5 px-3">Assigned Subjects</th>
                        <th scope="col" class="py-3.5 px-3">Contact</th>
                        <th scope="col" class="py-3.5 px-3">Status</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @php
                        $teachers = [
                            [
                                'name' => 'Prof. Virak Meas',
                                'email' => 'virak.meas@school.edu',
                                'qualification' => 'Master of Computer Science',
                                'specialization' => 'Web Development',
                                'subjects' => ['Web Development', 'Algorithms'],
                                'classrooms' => ['Grade 10-A', 'Grade 12-A'],
                                'phone' => '012 998 123',
                                'status' => 'active'
                            ],
                            [
                                'name' => 'Dr. Sopheap Ouk',
                                'email' => 'sopheap.ouk@school.edu',
                                'qualification' => 'Ph.D. in Pure Mathematics',
                                'specialization' => 'Advanced Calculus',
                                'subjects' => ['Mathematics', 'Statistics'],
                                'classrooms' => ['Grade 11-B', 'Grade 12-A'],
                                'phone' => '011 445 789',
                                'status' => 'active'
                            ],
                            [
                                'name' => 'Ms. Kunthea Chea',
                                'email' => 'kunthea.chea@school.edu',
                                'qualification' => 'Bachelor of Education (English)',
                                'specialization' => 'English Literature',
                                'subjects' => ['English Communications'],
                                'classrooms' => ['Grade 10-A', 'Grade 9-C'],
                                'phone' => '078 221 004',
                                'status' => 'active'
                            ],
                            [
                                'name' => 'Mr. Samnang Heng',
                                'email' => 'samnang.heng@school.edu',
                                'qualification' => 'M.Sc. in Applied Physics',
                                'specialization' => 'General Science',
                                'subjects' => ['Physics', 'Lab Experiments'],
                                'classrooms' => ['Grade 11-B'],
                                'phone' => '093 111 678',
                                'status' => 'active'
                            ],
                        ];
                    @endphp

                    @foreach($teachers as $teacher)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 pl-6 pr-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-sky-500 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($teacher['name'], 6, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 leading-snug">{{ $teacher['name'] }}</p>
                                        <p class="text-xs text-slate-400">{{ $teacher['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-3">
                                <p class="text-xs font-semibold text-slate-800">{{ $teacher['qualification'] }}</p>
                                <p class="text-[11px] text-indigo-600 font-medium">{{ $teacher['specialization'] }}</p>
                            </td>
                            <td class="py-4 px-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($teacher['subjects'] as $subj)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-xs font-medium border border-indigo-100">
                                            {{ $subj }}
                                        </span>
                                    @endforeach
                                </div>
                                <p class="text-[11px] text-slate-400 mt-1">Classes: {{ implode(', ', $teacher['classrooms']) }}</p>
                            </td>
                            <td class="py-4 px-3 font-mono text-xs text-slate-600">
                                {{ $teacher['phone'] }}
                            </td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active Faculty
                                </span>
                            </td>
                            <td class="py-4 pl-3 pr-6 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button" title="View Profile" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>
                                    <button type="button" title="Assign Subjects" class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Teacher Modal -->
    <div x-show="addTeacherModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            <div x-show="addTeacherModal" @click="addTeacherModal = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs"></div>
            <div x-show="addTeacherModal" class="relative bg-white rounded-2xl max-w-lg w-full p-6 text-left shadow-xl border border-slate-200/80 my-8 z-10">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Add Faculty Member</h3>
                    <button @click="addTeacherModal = false" class="p-1 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="#" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Teacher Full Name</label>
                        <input type="text" placeholder="e.g. Prof. Virak Meas" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address</label>
                            <input type="email" placeholder="faculty@school.edu" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Phone Number</label>
                            <input type="text" placeholder="012 345 678" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 font-mono">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Degree / Qualification</label>
                            <input type="text" placeholder="Master of Science" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Specialization</label>
                            <input type="text" placeholder="e.g. Mathematics" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="addTeacherModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 rounded-xl border border-slate-200">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm">Save Faculty</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
