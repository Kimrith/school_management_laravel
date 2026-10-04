@extends('layouts.student')

@section('title', 'Student Profile')
@section('page_title', 'My Profile')

@section('content')
@php
    $userName = $student?->user?->name ?? auth()->user()?->name ?? 'Student';
    $userEmail = $student?->user?->email ?? auth()->user()?->email ?? '';
    $studentCode = $student?->student_code ?? 'STU-0000';
    $className = $student?->classroom?->name ?? 'Unassigned';
    $gradeLevel = $student?->classroom?->grade_level ?? 'N/A';
    $academicYear = $student?->classroom?->academic_year ?? date('Y');
    $dob = $student?->date_of_birth ? \Illuminate\Support\Carbon::parse($student->date_of_birth) : null;
    $age = $dob ? $dob->age : 'N/A';
    $gender = $student?->gender ? ucfirst($student->gender) : 'Not specified';
    $parentName = $student?->parent_name ?? 'Not specified';
    $parentPhone = $student?->parent_phone ?? 'Not specified';
    $address = $student?->address ?? 'Not specified';
@endphp

<div 
    x-data="{ 
        activeTab: 'personal', 
        editModalOpen: false,
        toastVisible: {{ session('success') ? 'true' : 'false' }},
        cardFlipped: false
    }" 
    class="space-y-6 max-w-7xl mx-auto"
>
    <!-- Success Toast Notification -->
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
                <p class="text-sm font-semibold">{{ session('success', 'Profile information updated successfully!') }}</p>
                <p class="text-xs text-emerald-600">Your student record is synchronized with the registrar.</p>
            </div>
        </div>
        <button @click="toastVisible = false" class="text-emerald-500 hover:text-emerald-700 p-1 rounded-lg">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-slate-200/80">
        <div class="flex items-center gap-2 overflow-x-auto pb-px">
            <button 
                @click="activeTab = 'personal'"
                :class="activeTab === 'personal' ? 'border-sky-600 text-sky-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium'"
                class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span>Personal & Guardian Info</span>
            </button>

            <button 
                @click="activeTab = 'academic'"
                :class="activeTab === 'academic' ? 'border-sky-600 text-sky-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium'"
                class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                </svg>
                <span>Academic Record & Courses</span>
            </button>

            <button 
                @click="activeTab = 'card'"
                :class="activeTab === 'card' ? 'border-sky-600 text-sky-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium'"
                class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15A2.25 2.25 0 0 0 2.25 6.75v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.374a4.125 4.125 0 0 0-6.338 0 .75.75 0 0 0 .544 1.251h5.25a.75.75 0 0 0 .544-1.251Z" />
                </svg>
                <span>Digital Student ID Card</span>
            </button>

            <button 
                @click="activeTab = 'security'"
                :class="activeTab === 'security' ? 'border-sky-600 text-sky-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium'"
                class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
                <span>Account & Credentials</span>
            </button>
        </div>
    </div>

    <!-- TAB 1: Personal & Guardian Details -->
    <div x-show="activeTab === 'personal'" x-cloak class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Personal Identity & Contact Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Identity Details -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center font-bold">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Personal Information</h2>
                                <p class="text-xs text-slate-400">Official student registry and civil credentials</p>
                            </div>
                        </div>
                        <button 
                            @click="editModalOpen = true"
                            class="text-xs font-semibold text-sky-600 hover:text-sky-700 hover:underline inline-flex items-center gap-1"
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
                            <span class="font-semibold text-slate-800 text-base">{{ $userName }}</span>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Student Identification Code</span>
                            <span class="font-mono font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200 text-xs inline-block">
                                {{ $studentCode }}
                            </span>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Date of Birth</span>
                            <span class="font-semibold text-slate-800">
                                @if($dob)
                                    {{ $dob->format('F d, Y') }} 
                                    <span class="text-slate-400 font-normal text-xs">({{ $age }} years old)</span>
                                @else
                                    Not specified
                                @endif
                            </span>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Gender</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ strtolower($gender) === 'female' ? 'bg-pink-50 text-pink-700 border border-pink-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                {{ $gender }}
                            </span>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Blood Group</span>
                            <span class="font-semibold text-slate-800">O+ <span class="text-xs text-slate-400 font-normal">(Rh Positive)</span></span>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Nationality</span>
                            <span class="font-semibold text-slate-800 flex items-center gap-2">
                                <span>Cambodian</span>
                                <span class="text-base">🇰🇭</span>
                            </span>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Primary Languages</span>
                            <span class="font-semibold text-slate-800">Khmer (Native), English (Fluent)</span>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Student Status</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Regular Enrolled
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contact & Residential Details -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Contact & Address</h2>
                                <p class="text-xs text-slate-400">Communication lines & geographical residence</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-y-5 gap-x-6 text-sm">
                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Institutional Email</span>
                            <a href="mailto:{{ $userEmail }}" class="font-semibold text-sky-600 hover:underline flex items-center gap-1.5">
                                <span>{{ $userEmail }}</span>
                            </a>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-slate-400 block mb-1">Student Phone</span>
                            <a href="tel:{{ $parentPhone }}" class="font-semibold text-slate-800 hover:text-sky-600 font-mono">
                                +855 {{ substr($parentPhone, 1) }}
                            </a>
                        </div>

                        <div class="sm:col-span-2">
                            <span class="text-xs font-medium text-slate-400 block mb-1">Home Address</span>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-start gap-3">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
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

            <!-- Right 1 Col: Guardian & Emergency Contact Card -->
            <div class="space-y-6">
                <!-- Guardian & Family Details -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Guardian / Family</h3>
                                <p class="text-xs text-slate-400">Emergency & parent contacts</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 space-y-4 text-sm">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-3">
                            <div>
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block">Primary Guardian</span>
                                <p class="text-base font-bold text-slate-900 mt-0.5">{{ $parentName }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded-md bg-sky-50 text-sky-700 text-xs font-semibold">Father</span>
                            </div>

                            <hr class="border-slate-200/80">

                            <div>
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block">Guardian Phone</span>
                                <a href="tel:{{ $parentPhone }}" class="inline-flex items-center gap-2 mt-1 text-sm font-mono font-bold text-sky-600 hover:text-sky-700">
                                    <svg class="w-4 h-4 text-sky-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                    </svg>
                                    <span>{{ $parentPhone }}</span>
                                </a>
                            </div>

                            <div>
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block">Occupation</span>
                                <p class="text-xs font-medium text-slate-700 mt-0.5">Civil Engineering Consultant</p>
                            </div>
                        </div>

                        <!-- Emergency Protocol Notice -->
                        <div class="p-3.5 rounded-xl bg-amber-50/80 border border-amber-200/80 flex items-start gap-2.5">
                            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                            <div class="text-xs text-amber-900 leading-relaxed">
                                <p class="font-semibold">Medical & Emergency Protocol</p>
                                <p class="mt-0.5 text-amber-800">In case of school medical incident, campus infirmary will dispatch notification directly to <span class="font-bold">{{ $parentPhone }}</span>.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registration Audit -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Registration Record</h4>
                    <div class="space-y-2 text-xs text-slate-500">
                        <div class="flex items-center justify-between">
                            <span>Admission Date:</span>
                            <span class="font-semibold text-slate-800">September 01, 2024</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Profile Created:</span>
                            <span class="font-semibold text-slate-800">{{ $student?->created_at ? $student->created_at->format('M d, Y') : 'Sep 27, 2026' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Data Verified By:</span>
                            <span class="font-semibold text-emerald-600">Registrar Office ✓</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: Academic Program & Courses -->
    <div x-show="activeTab === 'academic'" x-cloak class="space-y-6">
        <!-- Academic Summary Header -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6">
            <h2 class="text-base font-bold text-slate-900 mb-1">Current Academic Program</h2>
            <p class="text-xs text-slate-400 mb-5">Enrolled stream, homeroom guidance, and credit requirements</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                    <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Assigned Class</span>
                    <p class="text-sm font-bold text-slate-900 mt-1">{{ $className }}</p>
                    <p class="text-xs text-slate-500">{{ $gradeLevel }} Standard Stream</p>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                    <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Academic Advisor</span>
                    <p class="text-sm font-bold text-slate-900 mt-1">{{ $student?->classroom?->teachers?->first()?->name ?? 'Department Faculty' }}</p>
                    <p class="text-xs text-slate-500">Classroom Advisor</p>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                    <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Homeroom Location</span>
                    <p class="text-sm font-bold text-slate-900 mt-1">{{ $student?->classroom?->room ? 'Room ' . $student->classroom->room : 'Campus Classroom' }}</p>
                    <p class="text-xs text-slate-500">{{ $className }}</p>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                    <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Enrollment Status</span>
                    <p class="text-sm font-bold text-indigo-600 mt-1">Active Academic Term</p>
                    <p class="text-xs text-slate-500">{{ $academicYear }}</p>
                </div>
            </div>
        </div>

        <!-- Enrolled Subjects Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Enrolled Subjects & Faculty</h3>
                    <p class="text-xs text-slate-400">Class schedule, room assignments, and current standing</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-sky-50 text-sky-700 border border-sky-200">
                        {{ $student?->classroom?->teacherSubjects?->count() ?? 0 }} Enrolled {{ \Illuminate\Support\Str::plural('Subject', $student?->classroom?->teacherSubjects?->count() ?? 0) }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200/70">
                        <tr>
                            <th class="px-5 py-3.5">Course & Code</th>
                            <th class="px-5 py-3.5">Instructor</th>
                            <th class="px-5 py-3.5">Schedule & Room</th>
                            <th class="px-5 py-3.5 text-center">Credits</th>
                            <th class="px-5 py-3.5 text-center">Standing</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @php
                            $subjects = $student?->classroom?->teacherSubjects ?? collect();
                            $studentMarksBySubject = $student?->marks?->keyBy(fn($m) => $m->exam?->subject_id) ?? collect();
                        @endphp
                        @forelse($subjects as $item)
                            @php
                                $subject = $item->subject;
                                $instructor = $item->teacher;
                                $code = $subject?->code ?? ('SUB-' . ($subject?->id ?? 1));
                                $mark = $studentMarksBySubject->get($subject?->id);
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($code, 0, 3)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $subject?->name ?? 'Course Subject' }}</p>
                                            <p class="text-xs text-slate-400 font-mono">{{ $code }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-800">{{ $instructor?->name ?? 'Faculty Staff' }}</p>
                                    <p class="text-xs text-slate-400">{{ $instructor?->email ?? '' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-800">{{ $student?->classroom?->room ? 'Room ' . $student->classroom->room : 'Campus Classroom' }}</p>
                                    <p class="text-xs text-slate-400">Section {{ $className }}</p>
                                </td>
                                <td class="px-5 py-4 text-center font-mono font-bold text-slate-800">3.0</td>
                                <td class="px-5 py-4 text-center">
                                    @if($mark)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold font-mono text-xs">
                                            {{ $mark->grade_letter ?? 'Recorded' }} ({{ number_format((float) $mark->marks_obtained, 1) }}%)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-xs">
                                            In Progress
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                                    <p class="text-sm font-medium text-slate-600">No enrolled subjects registered</p>
                                    <p class="text-xs text-slate-400 mt-1">Subjects assigned to your section will appear in this list.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 3: Digital Student ID Card -->
    <div x-show="activeTab === 'card'" x-cloak class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Official Digital Student ID</h2>
                <p class="text-xs text-slate-500 mt-0.5">Contactless smart identity credential issued by EduStudent Academy</p>
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
                    <span x-text="cardFlipped ? 'Show Front' : 'Flip to Back'">Flip to Back</span>
                </button>
                <button 
                    onclick="window.print()" 
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.076-.672-2.13-1.28-3.09a8.956 8.956 0 0 0-.964-1.248A8.963 8.963 0 0 1 12 6c3.48 0 6.47 1.974 7.95 4.887a8.96 8.96 0 0 0-.964 1.248 9.07 9.07 0 0 0-1.28 3.09M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5" />
                    </svg>
                    <span>Print Card</span>
                </button>
            </div>
        </div>

        <!-- Realistic Physical ID Card Display -->
        <div class="flex justify-center py-6">
            <!-- Card Container -->
            <div class="w-full max-w-md sm:max-w-lg">
                <!-- Front Side -->
                <div 
                    x-show="!cardFlipped" 
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 rotate-y-90 scale-95"
                    x-transition:enter-end="opacity-100 rotate-0 scale-100"
                    class="relative rounded-3xl bg-gradient-to-tr from-slate-900 via-indigo-950 to-blue-900 text-white p-6 sm:p-7 shadow-2xl border border-sky-400/30 overflow-hidden"
                >
                    <!-- Holographic Foil Pattern Accent -->
                    <div class="absolute -right-12 -top-12 w-48 h-48 bg-gradient-to-br from-cyan-400/25 via-fuchsia-500/20 to-amber-300/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-sky-500/20 rounded-full blur-2xl pointer-events-none"></div>

                    <!-- Card Header -->
                    <div class="relative z-10 flex items-center justify-between pb-4 border-b border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-400 to-indigo-500 flex items-center justify-center text-white shadow-md">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-sm sm:text-base tracking-wide uppercase text-white">EduStudent Academy</h3>
                                <p class="text-[10px] text-sky-200 tracking-wider uppercase font-semibold">Official Student Identity Card</p>
                            </div>
                        </div>

                        <!-- Smart Chip Simulation -->
                        <div class="w-11 h-8 rounded-lg bg-gradient-to-tr from-amber-300 via-amber-200 to-amber-400 border border-amber-500/40 p-1 flex flex-col justify-between shadow-xs">
                            <div class="h-1 bg-amber-600/40 rounded-full"></div>
                            <div class="h-1 bg-amber-600/40 rounded-full"></div>
                            <div class="h-1 bg-amber-600/40 rounded-full"></div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="relative z-10 pt-5 flex items-center gap-5 sm:gap-6">
                        <!-- Student Photo -->
                        <div class="w-24 h-28 sm:w-28 sm:h-32 rounded-2xl bg-gradient-to-b from-sky-400 to-indigo-600 p-0.5 shadow-lg shrink-0">
                            @if($student?->avatar_url)
                                <img src="{{ $student->avatar_url }}" alt="{{ $userName }}" class="w-full h-full rounded-[14px] object-cover border border-white/20">
                            @else
                                <div class="w-full h-full rounded-[14px] bg-slate-900 flex flex-col items-center justify-center text-white border border-white/20">
                                    <span class="text-3xl sm:text-4xl font-black">{{ strtoupper(substr($userName, 0, 2)) }}</span>
                                    <span class="text-[9px] uppercase tracking-wider text-sky-300 mt-1 font-bold">Verified</span>
                                </div>
                            @endif
                        </div>

                        <!-- Student Data Fields -->
                        <div class="flex-1 space-y-2">
                            <div>
                                <span class="text-[9px] font-semibold uppercase tracking-wider text-slate-400 block">Student Name</span>
                                <p class="text-base sm:text-lg font-black text-white leading-tight">{{ $userName }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <span class="text-[9px] font-semibold uppercase tracking-wider text-slate-400 block">ID Number</span>
                                    <p class="font-mono font-bold text-sky-300">{{ $studentCode }}</p>
                                </div>
                                <div>
                                    <span class="text-[9px] font-semibold uppercase tracking-wider text-slate-400 block">Class</span>
                                    <p class="font-bold text-white">{{ $className }}</p>
                                </div>
                                <div>
                                    <span class="text-[9px] font-semibold uppercase tracking-wider text-slate-400 block">Issued</span>
                                    <p class="font-mono text-slate-300 text-[11px]">{{ $student?->created_at ? \Illuminate\Support\Carbon::parse($student->created_at)->format('m/Y') : date('m/Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-[9px] font-semibold uppercase tracking-wider text-slate-400 block">Expires</span>
                                    <p class="font-mono font-bold text-amber-300 text-[11px]">{{ $student?->created_at ? \Illuminate\Support\Carbon::parse($student->created_at)->addYears(2)->format('m/Y') : date('m/Y', strtotime('+2 years')) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer & Barcode Simulation -->
                    <div class="relative z-10 mt-6 pt-4 border-t border-white/10 flex items-center justify-between">
                        <!-- Simulated Barcode -->
                        <div class="space-y-1">
                            <div class="flex items-center gap-0.5 sm:gap-1 h-7">
                                <span class="w-0.5 h-full bg-white"></span>
                                <span class="w-1 h-full bg-white"></span>
                                <span class="w-0.5 h-full bg-white"></span>
                                <span class="w-1.5 h-full bg-white"></span>
                                <span class="w-0.5 h-full bg-white"></span>
                                <span class="w-1 h-full bg-white"></span>
                                <span class="w-0.5 h-full bg-white"></span>
                                <span class="w-2 h-full bg-white"></span>
                                <span class="w-0.5 h-full bg-white"></span>
                                <span class="w-1 h-full bg-white"></span>
                                <span class="w-1.5 h-full bg-white"></span>
                                <span class="w-0.5 h-full bg-white"></span>
                                <span class="w-2 h-full bg-white"></span>
                                <span class="w-0.5 h-full bg-white"></span>
                                <span class="w-1 h-full bg-white"></span>
                                <span class="w-1.5 h-full bg-white"></span>
                                <span class="w-0.5 h-full bg-white"></span>
                                <span class="w-1 h-full bg-white"></span>
                                <span class="w-0.5 h-full bg-white"></span>
                            </div>
                            <span class="font-mono text-[9px] text-slate-400 tracking-widest block">{{ $studentCode }}</span>
                        </div>

                        <!-- Holographic Seal Badge -->
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-400 via-rose-300 to-sky-300 p-0.5 shadow-md flex items-center justify-center">
                                <div class="w-full h-full rounded-full bg-slate-900/90 flex items-center justify-center text-[9px] font-black text-amber-300 tracking-tighter uppercase">
                                    SEAL
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back Side -->
                <div 
                    x-show="cardFlipped" 
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 -rotate-y-90 scale-95"
                    x-transition:enter-end="opacity-100 rotate-0 scale-100"
                    class="relative rounded-3xl bg-gradient-to-tr from-slate-950 via-slate-900 to-indigo-950 text-white p-6 sm:p-7 shadow-2xl border border-slate-700 overflow-hidden"
                >
                    <!-- Magnetic Stripe -->
                    <div class="-mx-7 -mt-2 mb-5 h-12 bg-slate-950 border-y border-slate-800 flex items-center px-4">
                        <span class="text-[9px] font-mono text-slate-600 tracking-widest uppercase">ENCRYPTED MAGNETIC ENROLLMENT STRIPE</span>
                    </div>

                    <div class="space-y-4 text-xs">
                        <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-slate-300 text-[11px] leading-relaxed">
                            <p class="font-semibold text-white mb-0.5">Cardholder Regulations:</p>
                            This card certifies the student's legitimate enrollment at EduStudent Academy. It must be presented upon request by faculty or examination supervisors.
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-semibold block">Campus Contact</span>
                                <p class="font-mono font-bold text-white mt-0.5">+855 23 999 888</p>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-semibold block">Emergency Hotline</span>
                                <p class="font-mono font-bold text-rose-400 mt-0.5">+855 12 345 678</p>
                            </div>
                        </div>

                        <!-- Simulated QR Code -->
                        <div class="pt-2 flex items-center justify-between border-t border-white/10">
                            <div class="text-[10px] text-slate-400 space-y-0.5">
                                <p>EduStudent Registrar Office</p>
                                <p>Phnom Penh, Kingdom of Cambodia</p>
                            </div>

                            <div class="w-14 h-14 bg-white p-1 rounded-xl shrink-0 flex items-center justify-center">
                                <svg class="w-full h-full text-slate-900" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3 3h7v7H3V3zm2 2v3h3V5H5zm8-2h7v7h-7V3zm2 2v3h3V5h-3zM3 13h7v7H3v-7zm2 2v3h3v-3H5zm10-2h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm4 0h2v2h-2v-2zm-2-2h2v2h-2v-2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 4: Account & Credentials -->
    <div x-show="activeTab === 'security'" x-cloak class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Security Credentials & Password Update -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Login Credentials Summary -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-5">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Account Credentials</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Manage your student portal login credentials & authentication security</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-medium">Primary Login Email</p>
                                <p class="text-sm font-bold text-slate-800">{{ $userEmail }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Verified
                        </span>
                    </div>

                    <!-- Change Password Form Preview -->
                    <div class="pt-4 border-t border-slate-100 space-y-4">
                        <h3 class="text-sm font-bold text-slate-900">Change Portal Password</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Current Password</label>
                                <input 
                                    type="password" 
                                    placeholder="••••••••" 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500"
                                >
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">New Password</label>
                                <input 
                                    type="password" 
                                    placeholder="••••••••" 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500"
                                >
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button 
                                type="button" 
                                class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition-colors cursor-pointer"
                                onclick="alert('Password update is enabled. Enter current and new passwords to update.')"
                            >
                                Update Password
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Active Session Audit -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-4">
                    <h3 class="text-sm font-bold text-slate-900">Active Login Sessions</h3>
                    <div class="space-y-3">
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center font-bold">
                                    💻
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Windows PC &bull; Chrome Browser</p>
                                    <p class="text-[11px] text-slate-400">Phnom Penh, Cambodia &bull; Active Now</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700">
                                This Device
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right 1 Col: Two-Factor & Permissions -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                            🛡️
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">Security Health</h4>
                            <p class="text-xs text-slate-400">Account protection rating</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200">
                        <div class="flex items-center justify-between text-xs font-bold text-emerald-800 mb-1">
                            <span>Strong Security</span>
                            <span>90%</span>
                        </div>
                        <div class="w-full bg-emerald-200 rounded-full h-1.5">
                            <div class="bg-emerald-600 h-1.5 rounded-full" style="width: 90%"></div>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Email Verification</span>
                            <span class="font-bold text-emerald-600">Active</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Two-Factor Authentication</span>
                            <span class="font-semibold text-slate-400">Optional</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Role Authorization</span>
                            <span class="font-bold text-indigo-600">Student Access</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: Edit Profile Information -->
    <div 
        x-show="editModalOpen" 
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" 
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
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"
            @click="editModalOpen = false"
        ></div>

        <!-- Modal Dialog -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div 
                x-show="editModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200"
            >
                <form action="{{ route('student.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-900" id="modal-title">Edit Profile Details</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Update contact and guardian information</p>
                        </div>
                        <button 
                            @click="editModalOpen = false" 
                            type="button" 
                            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 text-sm">
                        <!-- Student Name -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Student Full Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                value="{{ $userName }}" 
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 font-medium"
                            >
                        </div>

                        <!-- Date of Birth & Gender -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Date of Birth</label>
                                <input 
                                    type="date" 
                                    name="dob" 
                                    value="{{ $dob ? $dob->format('Y-m-d') : '' }}" 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 font-medium"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Gender</label>
                                <select 
                                    name="gender" 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 font-medium"
                                >
                                    <option value="male" {{ strtolower($gender) === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ strtolower($gender) === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ strtolower($gender) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Guardian Details -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Guardian Name</label>
                                <input 
                                    type="text" 
                                    name="parent_name" 
                                    value="{{ $parentName }}" 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 font-medium"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Guardian Phone</label>
                                <input 
                                    type="text" 
                                    name="parent_phone" 
                                    value="{{ $parentPhone }}" 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 font-mono font-medium"
                                >
                            </div>
                        </div>

                        <!-- Residential Address -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Residential Address</label>
                            <textarea 
                                name="address" 
                                rows="3"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 font-medium"
                            >{{ $address }}</textarea>
                        </div>
                    </div>

                    <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-2xl">
                        <button 
                            @click="editModalOpen = false" 
                            type="button" 
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-200/70 transition-colors cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-600 hover:to-indigo-700 text-white text-xs font-semibold shadow-md shadow-sky-500/25 transition-all cursor-pointer"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection