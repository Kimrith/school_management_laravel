@extends('layouts.teacher')

@section('title', 'Faculty Profile')
@section('page_title', 'My Profile')

@section('content')
@php
    $facultyName = $user?->name ?? 'Prof. Virak Meas';
    $facultyEmail = $user?->email ?? 'virak.meas@school.edu';
    $facultyPhone = $teacher?->phone ?? '+855 12 345 678';
    $qualification = $teacher?->qualification ?? 'Master of Computer Science (M.Sc)';
    $specialization = $teacher?->specialization ?? 'Web Application Engineering & Database Systems';
    $address = $teacher?->address ?? 'St. 271, Sangkat Boeung Tumpun, Khan Mean Chey, Phnom Penh, Cambodia';
    $staffId = 'FAC-' . str_pad($teacher?->id ?? 108, 4, '0', STR_PAD_LEFT);
    $joinedDate = \Illuminate\Support\Carbon::parse($user?->created_at ?? '2023-09-01')->format('F d, Y');
    $yearsOfService = \Illuminate\Support\Carbon::parse($user?->created_at ?? '2023-09-01')->diffInYears(now()) ?: 1;

    $classCount = $assignedClassrooms->count() ?: 2;
    $subjectCount = $assignedSubjects->count() ?: 2;
    $studentCount = $totalStudents ?: 78;
@endphp

<div 
    x-data="{ 
        activeTab: '{{ $errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation') ? 'security' : (session('tab') ?? 'overview') }}', 
        editModalOpen: {{ $errors->has('name') || $errors->has('phone') || $errors->has('qualification') || $errors->has('specialization') || $errors->has('address') ? 'true' : 'false' }},
        cardFlipped: false,
        toastVisible: {{ session('success') || session('error') ? 'true' : 'false' }}
    }" 
    class="space-y-6 max-w-7xl mx-auto"
>
    <!-- Success & Error Toast Alerts -->
    @if(session('success'))
    <div 
        x-show="toastVisible" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl flex items-center justify-between shadow-xs"
    >
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold">{{ session('success') }}</p>
                <p class="text-xs text-emerald-600">Your faculty credentials are synchronized with the academic system.</p>
            </div>
        </div>
        <button @click="toastVisible = false" class="text-emerald-500 hover:text-emerald-700 p-1 rounded-lg">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl flex items-start gap-3 shadow-xs">
        <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-bold">Please correct the following errors:</p>
            <ul class="mt-1 list-disc list-inside text-xs space-y-0.5 text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Faculty Hero Banner -->
    <div class="relative bg-gradient-to-r from-emerald-800 via-teal-900 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl overflow-hidden border border-emerald-500/20">
        <!-- Ambient Background Glows -->
        <div class="absolute -right-16 -top-16 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -bottom-16 w-60 h-60 bg-teal-400/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Faculty Identity -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                <div class="relative group">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-gradient-to-tr from-emerald-500 via-teal-400 to-cyan-300 p-0.5 shadow-lg shadow-emerald-950/40">
                        <div class="w-full h-full bg-slate-900/90 rounded-[14px] flex items-center justify-center text-white font-bold text-3xl font-mono">
                            {{ strtoupper(substr($facultyName, 0, 2)) }}
                        </div>
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 ring-4 ring-slate-900 flex items-center justify-center" title="Active Faculty">
                        <span class="w-2 h-2 rounded-full bg-white"></span>
                    </span>
                </div>

                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">{{ $facultyName }}</h1>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Faculty Active
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-mono font-bold bg-white/10 text-white/90 border border-white/15">
                            {{ $staffId }}
                        </span>
                    </div>

                    <p class="text-emerald-200/90 text-sm font-medium flex items-center gap-2 flex-wrap">
                        <span>{{ $qualification }}</span>
                        <span>&bull;</span>
                        <span class="text-slate-300">{{ $specialization }}</span>
                    </p>

                    <div class="flex items-center gap-4 text-xs text-emerald-100/70 pt-1">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                            </svg>
                            Dept. of Computer Science &amp; Web Engineering
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            Tenured Since {{ $joinedDate }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Action Toolbar -->
            <div class="flex items-center gap-3">
                <button 
                    @click="editModalOpen = true"
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-emerald-900 hover:bg-emerald-50 text-xs sm:text-sm font-bold shadow-md transition-all cursor-pointer"
                >
                    <svg class="w-4 h-4 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    <span>Edit Profile</span>
                </button>

                <button 
                    @click="activeTab = 'card'"
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700/60 hover:bg-emerald-700/80 text-white text-xs sm:text-sm font-semibold border border-emerald-400/30 transition-all cursor-pointer"
                >
                    <svg class="w-4 h-4 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15A2.25 2.25 0 0 0 2.25 6.75v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.374a4.125 4.125 0 0 0-6.338 0 .75.75 0 0 0 .544 1.251h5.25a.75.75 0 0 0 .544-1.251Z" />
                    </svg>
                    <span>Faculty ID Card</span>
                </button>
            </div>
        </div>

        <!-- Metric Counters Ribbon -->
        <div class="relative z-10 grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/10 text-white">
            <div class="space-y-1">
                <span class="text-[11px] font-semibold tracking-wider text-emerald-200/80 uppercase">Assigned Classes</span>
                <p class="text-2xl font-extrabold font-mono tracking-tight">{{ $classCount }} <span class="text-xs font-normal text-emerald-200 font-sans">Sections</span></p>
            </div>

            <div class="space-y-1">
                <span class="text-[11px] font-semibold tracking-wider text-emerald-200/80 uppercase">Taught Subjects</span>
                <p class="text-2xl font-extrabold font-mono tracking-tight">{{ $subjectCount }} <span class="text-xs font-normal text-emerald-200 font-sans">Courses</span></p>
            </div>

            <div class="space-y-1">
                <span class="text-[11px] font-semibold tracking-wider text-emerald-200/80 uppercase">Enrolled Students</span>
                <p class="text-2xl font-extrabold font-mono tracking-tight">{{ $studentCount }} <span class="text-xs font-normal text-emerald-200 font-sans">Students</span></p>
            </div>

            <div class="space-y-1">
                <span class="text-[11px] font-semibold tracking-wider text-emerald-200/80 uppercase">Teaching Load</span>
                <p class="text-2xl font-extrabold font-mono tracking-tight">16 <span class="text-xs font-normal text-emerald-200 font-sans">Hrs / Week</span></p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-slate-200/80">
        <div class="flex items-center gap-2 overflow-x-auto pb-px">
            <button 
                @click="activeTab = 'overview'"
                :class="activeTab === 'overview' ? 'border-emerald-600 text-emerald-700 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium'"
                class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span>Faculty Bio &amp; Credentials</span>
            </button>

            <button 
                @click="activeTab = 'assignments'"
                :class="activeTab === 'assignments' ? 'border-emerald-600 text-emerald-700 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium'"
                class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                </svg>
                <span>Assigned Classes &amp; Schedule</span>
            </button>

            <button 
                @click="activeTab = 'card'"
                :class="activeTab === 'card' ? 'border-emerald-600 text-emerald-700 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium'"
                class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15A2.25 2.25 0 0 0 2.25 6.75v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.374a4.125 4.125 0 0 0-6.338 0 .75.75 0 0 0 .544 1.251h5.25a.75.75 0 0 0 .544-1.251Z" />
                </svg>
                <span>Digital Faculty ID Card</span>
            </button>

            <button 
                @click="activeTab = 'security'"
                :class="activeTab === 'security' ? 'border-emerald-600 text-emerald-700 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium'"
                class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
                <span>Account &amp; Security</span>
            </button>
        </div>
    </div>

    <!-- TAB 1: Faculty Overview & Bio -->
    <div x-show="activeTab === 'overview'" x-cloak class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Columns -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Academic Credentials & Legal Identity -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Faculty Credentials &amp; Registry</h2>
                                <p class="text-xs text-slate-400">Official university appointment and civil records</p>
                            </div>
                        </div>

                        <button 
                            @click="editModalOpen = true"
                            type="button"
                            class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 hover:underline inline-flex items-center gap-1 cursor-pointer"
                        >
                            <span>Edit</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-y-5 gap-x-6 text-sm">
                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Full Legal Name</span>
                            <span class="font-bold text-slate-900 text-base">{{ $facultyName }}</span>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Faculty Staff Code</span>
                            <span class="font-mono font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200 text-xs inline-block">
                                {{ $staffId }}
                            </span>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Highest Qualification</span>
                            <span class="font-semibold text-slate-800 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                                </svg>
                                {{ $qualification }}
                            </span>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Specialization</span>
                            <span class="font-semibold text-slate-800">{{ $specialization }}</span>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Academic Rank</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Senior Faculty Lecturer
                            </span>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Faculty Status</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Regular Full-Time
                            </span>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Teaching Languages</span>
                            <span class="font-medium text-slate-800">English (Academic C1), Khmer (Native)</span>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Nationality &amp; Work Eligibility</span>
                            <span class="font-medium text-slate-800 flex items-center gap-2">
                                <span>Cambodian</span>
                                <span class="text-base">🇰🇭</span>
                                <span class="text-xs text-slate-400">(Permanent Citizen)</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contact & Campus Office Details -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Communication &amp; Campus Office</h2>
                                <p class="text-xs text-slate-400">Institutional email, direct phone, and office room</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-y-5 gap-x-6 text-sm">
                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Institutional Email</span>
                            <a href="mailto:{{ $facultyEmail }}" class="font-semibold text-emerald-700 hover:underline flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                <span>{{ $facultyEmail }}</span>
                            </a>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Direct Phone</span>
                            <a href="tel:{{ $facultyPhone }}" class="font-bold text-slate-800 hover:text-emerald-700 font-mono flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                </svg>
                                <span>{{ $facultyPhone }}</span>
                            </a>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Office Room Location</span>
                            <p class="font-semibold text-slate-800">Faculty Tower B, Room 304 (Level 3)</p>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Student Office Hours</span>
                            <p class="font-semibold text-slate-800">Mon &amp; Wed &bull; 02:00 PM &ndash; 04:30 PM</p>
                        </div>

                        <div class="sm:col-span-2">
                            <span class="text-xs font-medium text-slate-400 block mb-1">Residence / Mailing Address</span>
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-start gap-3">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 0 1 15 0Z" />
                                </svg>
                                <div>
                                    <p class="font-medium text-slate-800">{{ $address }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Kingdom of Cambodia</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right 1 Column: Department & Faculty Info Card -->
            <div class="space-y-6">
                <!-- Department Hierarchy Card -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Academic Division</h3>
                                <p class="text-xs text-slate-400">Department affiliation</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 space-y-4 text-sm">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-3">
                            <div>
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block">Faculty Division</span>
                                <p class="text-sm font-bold text-slate-900 mt-0.5">Faculty of Science &amp; Technology</p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-xs font-semibold">CS &amp; IT Dept</span>
                            </div>

                            <hr class="border-slate-200/80">

                            <div>
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block">Department Dean</span>
                                <p class="text-sm font-semibold text-slate-800 mt-0.5">Dr. Chan Vanna, Ph.D.</p>
                                <p class="text-xs text-slate-400">dean.cst@school.edu</p>
                            </div>

                            <div>
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block">Curriculum Role</span>
                                <p class="text-xs font-medium text-slate-700 mt-0.5">Lead Software Architecture Evaluator &bull; Year 4 Capstone Committee</p>
                            </div>
                        </div>

                        <!-- Campus Emergency Notice -->
                        <div class="p-3.5 rounded-xl bg-emerald-50/80 border border-emerald-200/80 flex items-start gap-2.5">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                            <div class="text-xs text-emerald-900 leading-relaxed">
                                <p class="font-semibold">Campus Gate &amp; Lab Access</p>
                                <p class="mt-0.5 text-emerald-800">Your digital faculty pass grants 24/7 access to Computer Laboratories 301, 302, and Server Room A.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fast Shortcuts -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 space-y-3">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Teacher Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ url('/teacher/attendance') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-100 text-xs font-semibold text-slate-700 transition-colors">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span>Mark Classroom Attendance</span>
                            </span>
                            <span class="text-slate-400">&rarr;</span>
                        </a>

                        <a href="{{ url('/teacher/grades') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-100 text-xs font-semibold text-slate-700 transition-colors">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <span>Input Examination Marks</span>
                            </span>
                            <span class="text-slate-400">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: Assigned Classes & Schedule -->
    <div x-show="activeTab === 'assignments'" x-cloak class="space-y-6">
        <!-- Assigned Subjects Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Assigned Teaching Curriculum</h2>
                        <p class="text-xs text-slate-400">Courses and lectures assigned to you for Academic Year 2025-2026</p>
                    </div>
                </div>

                <a href="{{ url('/teacher/attendance') }}" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-colors self-start sm:self-auto">
                    Take Today's Attendance
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                            <th class="py-3.5 pl-6 pr-3">Subject / Course</th>
                            <th class="py-3.5 px-3">Subject Code</th>
                            <th class="py-3.5 px-3">Assigned Class</th>
                            <th class="py-3.5 px-3">Schedule Slot</th>
                            <th class="py-3.5 px-3">Room</th>
                            <th class="py-3.5 pl-3 pr-6 text-right">Quick Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @if($assignedSubjects->isNotEmpty())
                            @foreach($assignedSubjects as $subject)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-4 pl-6 pr-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($subject->name, 0, 3)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $subject->name }}</p>
                                            <p class="text-xs text-slate-400">Credit Hours: 4.0</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-3 font-mono text-xs font-bold text-slate-800">
                                    {{ $subject->code ?? 'SUB-'.str_pad($subject->id, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-4 px-3">
                                    @php
                                        $class = $assignedClassrooms->first();
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 text-xs font-semibold">
                                        {{ $class?->name ?? 'Grade 10-A' }}
                                    </span>
                                </td>
                                <td class="py-4 px-3 text-xs font-medium text-slate-800">
                                    Mon &amp; Wed &bull; 08:00 &ndash; 09:30 AM
                                </td>
                                <td class="py-4 px-3 text-xs font-semibold text-slate-700 font-mono">
                                    Room 302
                                </td>
                                <td class="py-4 pl-3 pr-6 text-right">
                                    <a href="{{ url('/teacher/attendance') }}" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-semibold transition-colors">
                                        Attendance
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <!-- Fallback Mock / Default Subjects for display -->
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-4 pl-6 pr-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">
                                            WEB
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">Web Application Development</p>
                                            <p class="text-xs text-slate-400">Full-stack Web Engineering (PHP, Laravel, JS)</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-3 font-mono text-xs font-bold text-slate-800">
                                    WEB401
                                </td>
                                <td class="py-4 px-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-semibold border border-emerald-200">
                                        Grade 10-A
                                    </span>
                                </td>
                                <td class="py-4 px-3 text-xs font-medium text-slate-800">
                                    Mon &amp; Wed &bull; 08:00 &ndash; 09:30 AM
                                </td>
                                <td class="py-4 px-3 text-xs font-semibold text-slate-700 font-mono">
                                    Room 302
                                </td>
                                <td class="py-4 pl-3 pr-6 text-right">
                                    <a href="{{ url('/teacher/attendance') }}" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-semibold transition-colors">
                                        Attendance
                                    </a>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-4 pl-6 pr-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-800 font-bold text-xs flex items-center justify-center shrink-0">
                                            DBS
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">Relational Database Systems</p>
                                            <p class="text-xs text-slate-400">Advanced SQL, Query Optimization &amp; Schema Design</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-3 font-mono text-xs font-bold text-slate-800">
                                    DBS301
                                </td>
                                <td class="py-4 px-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-800 text-xs font-semibold border border-indigo-200">
                                        Grade 12-A
                                    </span>
                                </td>
                                <td class="py-4 px-3 text-xs font-medium text-slate-800">
                                    Wed &bull; 10:00 &ndash; 11:30 AM
                                </td>
                                <td class="py-4 px-3 text-xs font-semibold text-slate-700 font-mono">
                                    Room 402
                                </td>
                                <td class="py-4 pl-3 pr-6 text-right">
                                    <a href="{{ url('/teacher/grades') }}" class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-xs font-semibold transition-colors">
                                        Grades
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Assigned Classrooms Cards -->
        <div>
            <h3 class="text-base font-bold text-slate-900 mb-4">Assigned Classrooms</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md font-mono">Grade 10</span>
                            <h4 class="text-lg font-bold text-slate-900 mt-2">Classroom 10-A</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Academic Year 2025-2026</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-lg">
                            🏫
                        </div>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>38 Students Enrolled</span>
                        <a href="{{ url('/teacher/attendance') }}" class="text-emerald-700 font-semibold hover:underline">Attendance &rarr;</a>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md font-mono">Grade 12</span>
                            <h4 class="text-lg font-bold text-slate-900 mt-2">Classroom 12-A</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Academic Year 2025-2026</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-lg">
                            🏫
                        </div>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>40 Students Enrolled</span>
                        <a href="{{ url('/teacher/grades') }}" class="text-indigo-700 font-semibold hover:underline">Enter Marks &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 3: Digital Faculty ID Card -->
    <div x-show="activeTab === 'card'" x-cloak class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Official Smart Faculty Credential</h2>
                <p class="text-xs text-slate-500 mt-0.5">Contactless high-security faculty identification pass issued by Institute Academic Affairs</p>
            </div>
            <div class="flex items-center gap-3">
                <button 
                    @click="cardFlipped = !cardFlipped" 
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors cursor-pointer"
                >
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span x-text="cardFlipped ? 'Show Front Side' : 'Flip to Back Side'">Flip to Back Side</span>
                </button>
                <button 
                    onclick="window.print()" 
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.076-.672-2.13-1.28-3.09a8.956 8.956 0 0 0-.964-1.248A8.963 8.963 0 0 1 12 6c3.48 0 6.47 1.974 7.95 4.887a8.96 8.96 0 0 0-.964 1.248 9.07 9.07 0 0 0-1.28 3.09M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5" />
                    </svg>
                    <span>Print Faculty Card</span>
                </button>
            </div>
        </div>

        <!-- Realistic Physical ID Card Display -->
        <div class="flex justify-center py-6 print-container">
            <div class="w-full max-w-md sm:max-w-lg">
                <!-- Front Side -->
                <div 
                    x-show="!cardFlipped" 
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="relative rounded-3xl bg-gradient-to-tr from-slate-950 via-emerald-950 to-teal-900 text-white p-6 sm:p-7 shadow-2xl border border-emerald-400/30 overflow-hidden"
                >
                    <!-- Holographic Foil Pattern Accent -->
                    <div class="absolute -right-12 -top-12 w-48 h-48 bg-gradient-to-br from-emerald-400/25 via-teal-500/20 to-amber-300/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-teal-500/20 rounded-full blur-2xl pointer-events-none"></div>

                    <!-- Card Header -->
                    <div class="relative z-10 flex items-center justify-between pb-4 border-b border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-400 to-teal-500 flex items-center justify-center text-white shadow-md">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-sm sm:text-base tracking-wide uppercase text-white">EduAcademy Institute</h3>
                                <p class="text-[10px] text-emerald-300 tracking-wider uppercase font-semibold">Official Faculty Smart Pass</p>
                            </div>
                        </div>

                        <!-- Microchip graphic -->
                        <div class="w-10 h-7 rounded-md bg-gradient-to-tr from-amber-300 to-amber-500 border border-amber-200/80 shadow-inner flex items-center justify-center opacity-90">
                            <div class="w-6 h-4 border border-amber-700/40 rounded-xs grid grid-cols-2 gap-0.5 p-0.5">
                                <div class="bg-amber-600/30"></div>
                                <div class="bg-amber-600/30"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="relative z-10 py-5 flex items-center gap-5">
                        <div class="relative shrink-0">
                            <div class="w-24 h-28 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-300 p-0.5 shadow-lg">
                                <div class="w-full h-full bg-slate-900 rounded-[14px] flex flex-col items-center justify-center text-white">
                                    <span class="text-3xl font-extrabold font-mono text-emerald-300">{{ strtoupper(substr($facultyName, 0, 2)) }}</span>
                                    <span class="text-[9px] uppercase tracking-wider text-emerald-400/80 mt-1 font-semibold">FACULTY</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1.5 flex-1 min-w-0">
                            <h4 class="text-lg sm:text-xl font-extrabold text-white truncate leading-tight">{{ $facultyName }}</h4>
                            <p class="text-xs text-emerald-300 font-semibold truncate">{{ $qualification }}</p>
                            <p class="text-[11px] text-slate-300 leading-snug">Dept. of Computer Science</p>

                            <div class="pt-2 grid grid-cols-2 gap-2 text-[10px]">
                                <div>
                                    <span class="text-slate-400 block uppercase font-medium">Staff ID</span>
                                    <span class="font-mono font-bold text-white text-xs">{{ $staffId }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block uppercase font-medium">Valid Thru</span>
                                    <span class="font-mono font-bold text-emerald-300 text-xs">2027-10-31</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer: Contactless NFC & Barcode -->
                    <div class="relative z-10 pt-4 border-t border-white/10 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <!-- NFC icon -->
                            <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z" />
                            </svg>
                            <span class="text-[9px] font-mono tracking-widest text-slate-300 uppercase">ACADEMIC SMART PASS</span>
                        </div>

                        <!-- Barcode Simulation -->
                        <div class="flex items-center gap-0.5 bg-white/10 px-2 py-1 rounded-md">
                            <div class="w-0.5 h-5 bg-white"></div>
                            <div class="w-1 h-5 bg-white"></div>
                            <div class="w-0.5 h-5 bg-white"></div>
                            <div class="w-1.5 h-5 bg-white"></div>
                            <div class="w-0.5 h-5 bg-white"></div>
                            <div class="w-1 h-5 bg-white"></div>
                            <div class="w-0.5 h-5 bg-white"></div>
                            <div class="w-1.5 h-5 bg-white"></div>
                            <div class="w-0.5 h-5 bg-white"></div>
                        </div>
                    </div>
                </div>

                <!-- Back Side -->
                <div 
                    x-show="cardFlipped" 
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="relative rounded-3xl bg-gradient-to-tr from-slate-900 via-emerald-950 to-teal-950 text-white p-6 sm:p-7 shadow-2xl border border-emerald-400/30 overflow-hidden"
                >
                    <!-- Magnetic Stripe -->
                    <div class="absolute top-6 left-0 right-0 h-10 bg-slate-950 shadow-inner border-y border-white/5"></div>

                    <div class="pt-14 space-y-4">
                        <div class="text-[10px] text-slate-400 leading-relaxed space-y-1">
                            <p class="font-bold text-slate-300 uppercase">Terms &amp; Campus Regulations</p>
                            <p>This card is the official property of EduAcademy Institute. It is strictly non-transferable and must be presented upon request to campus safety personnel or automated laboratory scanner turnstiles.</p>
                            <p>If found, please return to: Office of Academic Registrar, Building A, Phnom Penh, Cambodia.</p>
                        </div>

                        <!-- Emergency Security Contacts -->
                        <div class="p-3 rounded-xl bg-white/5 border border-white/10 flex items-center justify-between text-[11px]">
                            <div>
                                <span class="text-slate-400 block text-[9px] uppercase font-semibold">Campus Security Hotline</span>
                                <span class="font-mono font-bold text-emerald-300">+855 23 999 888</span>
                            </div>
                            <div class="text-right">
                                <span class="text-slate-400 block text-[9px] uppercase font-semibold">Authorized Signature</span>
                                <span class="font-serif italic text-emerald-200">Chan Vanna</span>
                            </div>
                        </div>

                        <!-- QR Code Scanner Mockup -->
                        <div class="flex items-center justify-between pt-2 border-t border-white/10">
                            <div>
                                <span class="text-[9px] font-mono text-slate-400 uppercase tracking-wider">Gate Access QR Code</span>
                                <p class="text-[10px] text-emerald-400 font-mono">{{ $staffId }}-2026-OK</p>
                            </div>
                            <div class="w-12 h-12 bg-white rounded-lg p-1 flex items-center justify-center">
                                <svg class="w-10 h-10 text-slate-950" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3 3h6v6H3V3zm2 2v2h2V5H5zm8-2h6v6h-6V3zm2 2v2h2V5h-2zM3 13h6v6H3v-6zm2 2v2h2v-2H5zm13-2h3v3h-3v-3zm-5 0h3v1h-3v-1zm5 5h3v3h-3v-3zm-2-2h2v2h-2v-2zm-3 2h2v3h-2v-3zm0-4h2v2h-2v-2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 4: Account & Security -->
    <div x-show="activeTab === 'security'" x-cloak class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Password Update Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Change Account Password</h2>
                                <p class="text-xs text-slate-400">Ensure your teacher portal account is secured with a strong passphrase</p>
                            </div>
                        </div>
                    </div>

                    <form 
                        method="POST" 
                        action="{{ route('teacher.profile.password') }}" 
                        x-data="{ showCurrent: false, showNew: false, showConfirm: false }"
                        class="p-5 sm:p-6 space-y-4"
                    >
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Current Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    :type="showCurrent ? 'text' : 'password'" 
                                    name="current_password" 
                                    id="current_password" 
                                    required
                                    class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border {{ $errors->has('current_password') ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm text-slate-800"
                                    placeholder="Enter your current account password"
                                >
                                <button 
                                    type="button" 
                                    @click="showCurrent = !showCurrent" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                    title="Toggle password visibility"
                                >
                                    <svg x-show="!showCurrent" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <svg x-show="showCurrent" x-cloak class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    New Password <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        :type="showNew ? 'text' : 'password'" 
                                        name="password" 
                                        id="password" 
                                        required
                                        class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm text-slate-800"
                                        placeholder="Min. 8 characters"
                                    >
                                    <button 
                                        type="button" 
                                        @click="showNew = !showNew" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                        title="Toggle password visibility"
                                    >
                                        <svg x-show="!showNew" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        <svg x-show="showNew" x-cloak class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Confirm New Password <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        :type="showConfirm ? 'text' : 'password'" 
                                        name="password_confirmation" 
                                        id="password_confirmation" 
                                        required
                                        class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border {{ $errors->has('password_confirmation') ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm text-slate-800"
                                        placeholder="Repeat new password"
                                    >
                                    <button 
                                        type="button" 
                                        @click="showConfirm = !showConfirm" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                        title="Toggle password visibility"
                                    >
                                        <svg x-show="!showConfirm" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        <svg x-show="showConfirm" x-cloak class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 text-xs text-slate-500 flex items-start gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                            <span>Passwords must contain at least 8 characters. We recommend combining letters, numbers, and symbols.</span>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button 
                                type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold shadow-md transition-colors cursor-pointer"
                            >
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right 1 Col: Security Summary & 2FA Status -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 space-y-4">
                    <h3 class="text-sm font-bold text-slate-900">Security Checkup</h3>
                    
                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="font-medium text-slate-700">Account Role</span>
                            <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-semibold">Teacher</span>
                        </div>

                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="font-medium text-slate-700">Email Verification</span>
                            <span class="text-emerald-600 font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Verified
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="font-medium text-slate-700">Two-Factor Authentication</span>
                            <span class="text-slate-400 font-medium">Standard SSO</span>
                        </div>

                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="font-medium text-slate-700">Current Session</span>
                            <span class="text-slate-600 font-mono">Active (This device)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal Dialog -->
    <div 
        x-show="editModalOpen" 
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        role="dialog" 
        aria-modal="true"
    >
        <!-- Backdrop -->
        <div 
            x-show="editModalOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="editModalOpen = false"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"
        ></div>

        <!-- Modal Panel -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div 
                x-show="editModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-slate-100"
            >
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Edit Faculty Profile</h3>
                        <p class="text-xs text-slate-400">Update your academic qualifications and public contact information</p>
                    </div>
                    <button @click="editModalOpen = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('teacher.profile.update') }}" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="modal_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Full Name <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="modal_name" 
                            value="{{ old('name', $facultyName) }}" 
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm text-slate-800"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="modal_phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Phone Number
                            </label>
                            <input 
                                type="text" 
                                name="phone" 
                                id="modal_phone" 
                                value="{{ old('phone', $facultyPhone) }}"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm text-slate-800"
                            >
                        </div>

                        <div>
                            <label for="modal_qualification" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Academic Qualification
                            </label>
                            <input 
                                type="text" 
                                name="qualification" 
                                id="modal_qualification" 
                                value="{{ old('qualification', $qualification) }}" 
                                placeholder="e.g. Master of Computer Science"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm text-slate-800"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="modal_specialization" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Teaching Specialization &amp; Domain
                        </label>
                        <input 
                            type="text" 
                            name="specialization" 
                            id="modal_specialization" 
                            value="{{ old('specialization', $specialization) }}" 
                            placeholder="e.g. Software Engineering & Database Architecture"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm text-slate-800"
                        >
                    </div>

                    <div>
                        <label for="modal_address" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Office Room &amp; Address
                        </label>
                        <textarea 
                            name="address" 
                            id="modal_address" 
                            rows="2"
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm text-slate-800"
                        >{{ old('address', $address) }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button 
                            @click="editModalOpen = false" 
                            type="button" 
                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition-colors cursor-pointer"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .print-container, .print-container * {
            visibility: visible;
        }
        .print-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>
@endpush
@endsection
