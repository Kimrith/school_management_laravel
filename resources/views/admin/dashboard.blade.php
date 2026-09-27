@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header & Quick Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Academic Overview</h1>
            <p class="text-sm text-slate-500 mt-1">Welcome back, {{ auth()->user()->name ?? 'Administrator' }}! Here is what's happening in your school today.</p>
        </div>
        <div class="flex items-center gap-3">
            <a 
                href="{{ url('/admin/students/create') ?? '#' }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm hover:shadow-md transition-all duration-150 cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Student</span>
            </a>
            <button 
                type="button" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold border border-slate-200 shadow-2xs hover:border-slate-300 transition-colors cursor-pointer"
            >
                <svg class="w-4.5 h-4.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Export Report</span>
            </button>
        </div>
    </div>

    <!-- 4 Minimalist Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Students -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:border-slate-300 hover:shadow-sm transition-all duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Students</span>
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-900">{{ number_format($totalStudents ?? 1284) }}</span>
                <span class="inline-flex items-center text-xs font-semibold text-emerald-600 gap-0.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                    </svg>
                    +12.4%
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1">Active enrolled this academic year</p>
        </div>

        <!-- Card 2: Total Teachers -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:border-slate-300 hover:shadow-sm transition-all duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Teachers</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-900">{{ number_format($totalTeachers ?? 86) }}</span>
                <span class="inline-flex items-center text-xs font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                    6 Departments
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1">Full-time certified educators</p>
        </div>

        <!-- Card 3: Active Classes -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:border-slate-300 hover:shadow-sm transition-all duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Classes</span>
                <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-900">{{ number_format($totalClassrooms ?? 34) }}</span>
                <span class="inline-flex items-center text-xs font-medium text-violet-700 bg-violet-50 px-2 py-0.5 rounded-md">
                    100% Assigned
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1">Across Grades 7 to 12</p>
        </div>

        <!-- Card 4: Today's Attendance -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:border-slate-300 hover:shadow-sm transition-all duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Today's Attendance</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-900">{{ $attendanceRate ?? '96.8%' }}</span>
                <span class="inline-flex items-center text-xs font-semibold text-emerald-600 gap-0.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                    </svg>
                    +2.3%
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1">1,243 of 1,284 present today</p>
        </div>
    </div>

    <!-- Main Section: Data Table & Quick Insights -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Recent Student Registrations Table (2 Cols on Desktop) -->
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <!-- Table Header Toolbar -->
            <div class="p-5 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Recent Student Admissions</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Latest students enrolled into the system</p>
                </div>

                <div class="flex items-center gap-2.5">
                    <!-- Search Input -->
                    <div class="relative w-full sm:w-56">
                        <input 
                            type="text" 
                            placeholder="Filter by name, code..." 
                            class="w-full pl-8.5 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-xl placeholder-slate-400 text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                        >
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                    </div>

                    <!-- View All Link -->
                    <a 
                        href="{{ url('/admin/students') }}" 
                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 shrink-0 px-2 py-1.5 hover:bg-indigo-50 rounded-lg transition-colors"
                    >
                        View All
                    </a>
                </div>
            </div>

            <!-- Table Responsive Container -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                            <th scope="col" class="py-3.5 pl-6 pr-3">Student</th>
                            <th scope="col" class="py-3.5 px-3">Classroom</th>
                            <th scope="col" class="py-3.5 px-3">Gender</th>
                            <th scope="col" class="py-3.5 px-3">Parent / Contact</th>
                            <th scope="col" class="py-3.5 px-3">Status</th>
                            <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @php
                            $sampleStudents = [
                                [
                                    'name' => 'Sokha Chan',
                                    'code' => 'STU-1001',
                                    'class' => 'Grade 10-A',
                                    'grade' => 'Grade 10',
                                    'gender' => 'male',
                                    'parent' => 'Chan Dara',
                                    'phone' => '012 345 678',
                                    'status' => 'active',
                                    'initials' => 'SC',
                                    'bg' => 'bg-emerald-500'
                                ],
                                [
                                    'name' => 'Bopha Vong',
                                    'code' => 'STU-1002',
                                    'class' => 'Grade 10-A',
                                    'grade' => 'Grade 10',
                                    'gender' => 'female',
                                    'parent' => 'Vong Meas',
                                    'phone' => '015 889 221',
                                    'status' => 'active',
                                    'initials' => 'BV',
                                    'bg' => 'bg-indigo-500'
                                ],
                                [
                                    'name' => 'Dara Rath',
                                    'code' => 'STU-1003',
                                    'class' => 'Grade 11-B',
                                    'grade' => 'Grade 11',
                                    'gender' => 'male',
                                    'parent' => 'Rath Sitha',
                                    'phone' => '098 712 334',
                                    'status' => 'pending',
                                    'initials' => 'DR',
                                    'bg' => 'bg-amber-500'
                                ],
                                [
                                    'name' => 'Chanthou Seng',
                                    'code' => 'STU-1004',
                                    'class' => 'Grade 12-A',
                                    'grade' => 'Grade 12',
                                    'gender' => 'female',
                                    'parent' => 'Seng Kosal',
                                    'phone' => '077 445 109',
                                    'status' => 'active',
                                    'initials' => 'CS',
                                    'bg' => 'bg-sky-500'
                                ],
                                [
                                    'name' => 'Panha Lim',
                                    'code' => 'STU-1005',
                                    'class' => 'Grade 9-C',
                                    'grade' => 'Grade 9',
                                    'gender' => 'male',
                                    'parent' => 'Lim Vichea',
                                    'phone' => '089 990 123',
                                    'status' => 'unpaid',
                                    'initials' => 'PL',
                                    'bg' => 'bg-purple-500'
                                ],
                            ];
                        @endphp

                        @foreach($sampleStudents as $student)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <!-- Student Name & Code -->
                                <td class="py-3.5 pl-6 pr-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl {{ $student['bg'] }} text-white text-xs font-bold flex items-center justify-center shadow-2xs shrink-0">
                                            {{ $student['initials'] }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-900 leading-snug">{{ $student['name'] }}</p>
                                            <p class="text-xs font-mono text-slate-400">{{ $student['code'] }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Classroom -->
                                <td class="py-3.5 px-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium">
                                        {{ $student['class'] }}
                                    </span>
                                </td>

                                <!-- Gender -->
                                <td class="py-3.5 px-3 text-xs capitalize text-slate-600">
                                    {{ $student['gender'] }}
                                </td>

                                <!-- Parent Info -->
                                <td class="py-3.5 px-3">
                                    <p class="text-xs font-medium text-slate-800">{{ $student['parent'] }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $student['phone'] }}</p>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-3.5 px-3">
                                    @if($student['status'] === 'active')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Enrolled
                                        </span>
                                    @elseif($student['status'] === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Fees Due
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-3.5 pl-3 pr-6 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a 
                                            href="#" 
                                            title="View Profile" 
                                            class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>
                                        <a 
                                            href="#" 
                                            title="Edit Records" 
                                            class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Table Pagination / Summary Footer -->
            <div class="p-4 px-6 bg-slate-50/50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <p>Showing <span class="font-semibold text-slate-800">1</span> to <span class="font-semibold text-slate-800">5</span> of <span class="font-semibold text-slate-800">1,284</span> students</p>
                <div class="inline-flex items-center gap-1">
                    <button class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 disabled:opacity-50 font-medium">Previous</button>
                    <button class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white font-medium shadow-xs">1</button>
                    <button class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 font-medium">2</button>
                    <button class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 font-medium">3</button>
                    <button class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 font-medium">Next</button>
                </div>
            </div>
        </div>

        <!-- Right Side Widget Column (1 Col) -->
        <div class="space-y-6">
            <!-- Fee Collection Summary Card -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Term Fee Collection</h3>
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">85.6%</span>
                </div>
                
                <div class="mt-4">
                    <div class="flex items-baseline justify-between text-xs text-slate-500">
                        <span>Collected</span>
                        <span class="font-bold text-slate-900 text-base">$42,800 <span class="text-xs font-normal text-slate-400">/ $50,000</span></span>
                    </div>
                    <!-- Progress Bar -->
                    <div class="w-full h-2.5 rounded-full bg-slate-100 mt-2 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-emerald-500" style="width: 85.6%"></div>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3 text-center">
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <p class="text-xs text-slate-400">Paid Invoices</p>
                        <p class="text-sm font-bold text-slate-800 mt-0.5">342</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-rose-50/50 border border-rose-100">
                        <p class="text-xs text-rose-500">Unpaid / Due</p>
                        <p class="text-sm font-bold text-rose-700 mt-0.5">58</p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Examinations -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Upcoming Exams</h3>
                    <a href="{{ url('/admin/exams') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View All</a>
                </div>

                <div class="mt-4 space-y-3">
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-sky-100 text-sky-700 font-bold text-xs flex flex-col items-center justify-center shrink-0">
                            <span>15</span>
                            <span class="text-[9px] uppercase">Oct</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-800 truncate">Web Development Midterm</p>
                            <p class="text-[11px] text-slate-400">Grade 10-A &bull; 100 Marks</p>
                        </div>
                        <span class="text-[11px] font-medium text-sky-700 bg-sky-50 px-2 py-0.5 rounded-md">Exam</span>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-700 font-bold text-xs flex flex-col items-center justify-center shrink-0">
                            <span>22</span>
                            <span class="text-[9px] uppercase">Oct</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-800 truncate">Mathematics Final</p>
                            <p class="text-[11px] text-slate-400">Grade 12-A &bull; 100 Marks</p>
                        </div>
                        <span class="text-[11px] font-medium text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md">Exam</span>
                    </div>
                </div>
            </div>

            <!-- Quick Management Shortcuts -->
            <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-2xl p-5 text-white shadow-md">
                <h4 class="text-sm font-bold">Quick Academic Actions</h4>
                <p class="text-xs text-indigo-200 mt-1">Direct operations for administrators</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ url('/admin/attendances') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-medium text-white transition-colors">
                        <span>Record Today's Attendance</span>
                        <svg class="w-4 h-4 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                    <a href="{{ url('/admin/fees') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-medium text-white transition-colors">
                        <span>Generate Fee Invoices</span>
                        <svg class="w-4 h-4 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
