@extends('layouts.teacher')

@section('title', 'Faculty Profile')
@section('page_title', 'My Profile')

@section('content')
@php
    $facultyName = $user?->name ?? 'Faculty Member';
    $facultyEmail = $user?->email ?? '';
    $facultyPhone = $teacher?->phone;
    $qualification = $teacher?->qualification;
    $specialization = $teacher?->specialization;
    $address = $teacher?->address;
    $staffId = $teacher?->id ? 'FAC-' . str_pad($teacher->id, 4, '0', STR_PAD_LEFT) : 'FAC-0000';
    $joinedDate = $user?->created_at ? \Illuminate\Support\Carbon::parse($user->created_at)->format('F d, Y') : null;
    $yearsOfService = $user?->created_at ? \Illuminate\Support\Carbon::parse($user->created_at)->diffInYears(now()) : 0;

    $classCount = $assignedClassrooms->count();
    $subjectCount = $assignedSubjects->count();
    $studentCount = $totalStudents;
    $hours = $teachingLoadHours ?? ($teacherSubjects->count() * 3);
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

                    @if($qualification || $specialization)
                    <p class="text-emerald-200/90 text-sm font-medium flex items-center gap-2 flex-wrap">
                        @if($qualification)
                            <span>{{ $qualification }}</span>
                        @endif
                        @if($qualification && $specialization)
                            <span>&bull;</span>
                        @endif
                        @if($specialization)
                            <span class="text-slate-300">{{ $specialization }}</span>
                        @endif
                    </p>
                    @endif

                    <div class="flex items-center gap-4 text-xs text-emerald-100/70 pt-1 flex-wrap">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                            </svg>
                            Dept. of Computer Science &amp; Academic Staff
                        </span>
                        @if($joinedDate)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            Appointed Since {{ $joinedDate }}
                        </span>
                        @endif
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
                <p class="text-2xl font-extrabold font-mono tracking-tight">{{ $hours }} <span class="text-xs font-normal text-emerald-200 font-sans">Hrs / Week</span></p>
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

    <!-- Tab 1: Faculty Overview & Bio -->
    @include('teacher.profile._overview')

    <!-- Tab 2: Assigned Classes & Schedule -->
    @include('teacher.profile._assignments')

    <!-- Tab 3: Digital Faculty ID Card -->
    @include('teacher.profile._card')

    <!-- Tab 4: Account & Security -->
    @include('teacher.profile._security')

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
