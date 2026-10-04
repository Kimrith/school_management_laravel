@extends('layouts.admin')

@section('title', 'Edit Student - ' . $student->student_code)
@section('page_title', 'Edit Student')

@section('content')
@php
    $statusVal = old('status', $student->user->status?->value ?? $student->user->status ?? 'active');
    $isSuspended = $statusVal === 'suspended';
@endphp

<div class="w-full space-y-6">
    <!-- Header with Breadcrumbs & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-1">
                <a href="{{ route('admin.students.index') }}" class="hover:text-indigo-600 transition-colors">Students Directory</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Edit Student Profile</span>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    {{ $student->user->name ?? 'Student Record' }}
                </h1>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $isSuspended ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isSuspended ? 'bg-rose-500' : 'bg-emerald-500' }}"></span>
                    {{ ucfirst($statusVal) }}
                </span>
                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $student->student_code }}
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Modify student credentials, classroom registry, demographics, and parent contact information.
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <a 
                href="{{ route('admin.students.index') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-colors"
            >
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Back to Directory</span>
            </a>

            <button 
                type="submit" 
                form="edit-student-form"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs hover:shadow transition-all duration-150 cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <!-- Main Full-Page Flex Container -->
    <div class="flex flex-col lg:flex-row items-start gap-6">

        <!-- Left / Primary Form Column -->
        <div class="flex-1 w-full space-y-6 min-w-0">
            <form id="edit-student-form" action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Card 1: Credentials & Academic Placement -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs font-bold text-sm">
                                01
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 tracking-tight">Student Credentials & Academic Placement</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Account identity, portal login credentials, and classroom assignment</p>
                            </div>
                        </div>
                    </div>

                    <!-- Student Avatar Upload Field (formfile) -->
                    <div x-data="{ avatarPreview: '{{ $student->avatar_url }}' }" class="p-4 bg-slate-50/80 rounded-2xl border border-dashed @error('avatar') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-300/80 @enderror">
                        <label class="block font-semibold text-slate-700 text-xs mb-2">Student Photo / Avatar</label>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <!-- Avatar Preview -->
                            <div class="relative w-16 h-16 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0 overflow-hidden shadow-2xs">
                                <template x-if="avatarPreview">
                                    <img :src="avatarPreview" alt="Avatar preview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!avatarPreview">
                                    <div class="w-full h-full bg-gradient-to-tr from-sky-500 to-indigo-600 text-white font-bold text-base flex items-center justify-center">
                                        {{ strtoupper(substr($student->user->name ?? 'ST', 0, 2)) }}
                                    </div>
                                </template>
                            </div>

                            <!-- File input -->
                            <div class="flex-1 min-w-0">
                                <input 
                                    type="file" 
                                    name="avatar" 
                                    id="edit_student_avatar"
                                    accept="image/png,image/jpeg,image/jpg,image/webp,image/gif"
                                    @change="const file = $event.target.files[0]; if (file) { avatarPreview = URL.createObjectURL(file); }"
                                    class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:cursor-pointer cursor-pointer focus:outline-none"
                                >
                                <p class="text-[11px] text-slate-400 mt-1">Upload a replacement image (PNG, JPG, WEBP, or GIF up to 2MB). Leave empty to keep existing.</p>
                            </div>

                            <!-- Clear new selection button -->
                            <button 
                                type="button" 
                                x-show="avatarPreview && avatarPreview !== '{{ $student->avatar_url }}'" 
                                x-cloak
                                @click="avatarPreview = '{{ $student->avatar_url }}'; $el.closest('[x-data]').querySelector('input[type=file]').value = ''"
                                class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer text-xs flex items-center gap-1 font-semibold"
                                title="Reset to current avatar"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>Reset</span>
                            </button>
                        </div>
                        @error('avatar')
                            <p class="text-rose-600 text-[11px] mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Full Student Name *</label>
                            <input 
                                type="text" 
                                name="name" 
                                value="{{ old('name', $student->user->name ?? '') }}" 
                                required 
                                placeholder="e.g. Sopheak Chan"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('name') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400"
                            >
                            @error('name')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Email Address (Login) *</label>
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ old('email', $student->user->email ?? '') }}" 
                                required 
                                placeholder="student@school.edu"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('email') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400"
                            >
                            @error('email')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Student Code *</label>
                            <input 
                                type="text" 
                                name="student_code" 
                                value="{{ old('student_code', $student->student_code) }}" 
                                required 
                                placeholder="e.g. STU-1001"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('student_code') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-semibold placeholder:text-slate-400"
                            >
                            @error('student_code')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Classroom Assignment</label>
                            <select 
                                name="classroom_id" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('classroom_id') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-700"
                            >
                                <option value="">Select Classroom (Unassigned)...</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}" {{ (string) old('classroom_id', $student->classroom_id) === (string) $classroom->id ? 'selected' : '' }}>
                                        {{ $classroom->name }} (Grade {{ $classroom->grade_level }}) - {{ $classroom->academic_year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('classroom_id')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Account Status *</label>
                            <select 
                                name="status" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('status') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-700"
                            >
                                <option value="active" {{ $statusVal === 'active' ? 'selected' : '' }}>Active (Full Access)</option>
                                <option value="suspended" {{ $statusVal === 'suspended' ? 'selected' : '' }}>Suspended (Login Blocked)</option>
                                <option value="inactive" {{ $statusVal === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="pending" {{ $statusVal === 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            @error('status')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Gender *</label>
                            <select 
                                name="gender" 
                                required 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('gender') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-700"
                            >
                                <option value="male" {{ old('gender', strtolower((string)$student->gender)) === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', strtolower((string)$student->gender)) === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', strtolower((string)$student->gender)) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Demographics & Family Contact -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs font-bold text-sm">
                                02
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 tracking-tight">Demographics & Family Information</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Birth certificate records, guardian contact, and residential address</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Date of Birth</label>
                            <input 
                                type="date" 
                                name="date_of_birth" 
                                value="{{ old('date_of_birth', $student->date_of_birth ? \Illuminate\Support\Carbon::parse($student->date_of_birth)->format('Y-m-d') : '') }}" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('date_of_birth') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-700"
                            >
                            @error('date_of_birth')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Parent / Guardian Name</label>
                            <input 
                                type="text" 
                                name="parent_name" 
                                value="{{ old('parent_name', $student->parent_name) }}" 
                                placeholder="e.g. Socheat Chan" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('parent_name') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400"
                            >
                            @error('parent_name')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1.5">Parent / Guardian Contact Phone</label>
                            <input 
                                type="text" 
                                name="parent_phone" 
                                value="{{ old('parent_phone', $student->parent_phone) }}" 
                                placeholder="012 345 678" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('parent_phone') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium placeholder:text-slate-400"
                            >
                            @error('parent_phone')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1.5">Residential Home Address</label>
                            <textarea 
                                name="address" 
                                rows="3" 
                                placeholder="e.g. #123, St. 271, Sangkat Boeung Tumpun, Khan Meanchey, Phnom Penh" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('address') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium resize-none placeholder:text-slate-400"
                            >{{ old('address', $student->address) }}</textarea>
                            @error('address')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Bottom Action Bar inside Form -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 flex items-center justify-between gap-4 shadow-xs">
                    <a 
                        href="{{ route('admin.students.index') }}" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition-colors"
                    >
                        Discard Changes
                    </a>

                    <div class="flex items-center gap-3">
                        <button 
                            type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs hover:shadow transition-all duration-150 cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            <span>Save Student Changes</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right Column / Sidebar Panel -->
        <div class="w-full lg:w-80 xl:w-96 shrink-0 space-y-6">

            <!-- Sidebar Card 1: Student Identity & Quick Stats -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-5">
                <div class="flex items-center gap-4">
                    @if($student->avatar_url)
                        <img src="{{ $student->avatar_url }}" alt="{{ $student->user->name ?? 'Student' }}" class="w-16 h-16 rounded-2xl object-cover shrink-0 shadow-md shadow-indigo-500/20 border-2 border-white ring-1 ring-slate-100">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-sky-500 via-indigo-600 to-indigo-700 text-white font-bold text-lg flex items-center justify-center shrink-0 shadow-md shadow-indigo-500/20 border-2 border-white ring-1 ring-slate-100">
                            {{ strtoupper(substr($student->user->name ?? 'ST', 0, 2)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h2 class="text-base font-bold text-slate-900 truncate">{{ $student->user->name ?? 'Student' }}</h2>
                        <p class="text-xs text-slate-400 font-mono font-medium">{{ $student->student_code }}</p>
                        <div class="mt-1.5 flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $isSuspended ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $isSuspended ? 'bg-rose-500' : 'bg-emerald-500' }}"></span>
                                {{ ucfirst($statusVal) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 gap-3 pt-4 border-t border-slate-100">
                    <div class="p-3 bg-slate-50/80 rounded-2xl border border-slate-100">
                        <span class="block text-[11px] font-medium text-slate-400">Classroom</span>
                        <span class="text-sm font-bold text-slate-900 mt-0.5 block truncate">
                            {{ $student->classroom->name ?? 'Unassigned' }}
                        </span>
                    </div>

                    <div class="p-3 bg-slate-50/80 rounded-2xl border border-slate-100">
                        <span class="block text-[11px] font-medium text-slate-400">Gender</span>
                        <span class="text-sm font-bold text-slate-900 mt-0.5 block capitalize">
                            {{ $student->gender ?? 'Not set' }}
                        </span>
                    </div>
                </div>

                <!-- Details List -->
                <div class="space-y-2.5 pt-2 text-xs">
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="text-slate-400">Login Email</span>
                        <span class="font-medium text-slate-800 text-right truncate max-w-[170px]">{{ $student->user->email ?? 'No email' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="text-slate-400">Parent / Guardian</span>
                        <span class="font-medium text-slate-800 text-right truncate max-w-[170px]">{{ $student->parent_name ?? 'Not set' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="text-slate-400">Guardian Contact</span>
                        <span class="font-mono font-medium text-slate-800">{{ $student->parent_phone ?? 'Not set' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="text-slate-400">Enrolled Since</span>
                        <span class="font-medium text-slate-800">{{ $student->created_at ? $student->created_at->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar Card 2: Classroom & Academic Placement Preview -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Academic Placement</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Enrolled grade level & section</p>
                    </div>
                    @if($student->classroom)
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold font-mono bg-indigo-50 text-indigo-700 border border-indigo-100">
                            {{ $student->classroom->grade_level }}
                        </span>
                    @endif
                </div>

                @if($student->classroom)
                    <div class="p-3.5 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white border border-indigo-200/80 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-indigo-950">{{ $student->classroom->name }}</p>
                                <p class="text-xs text-indigo-700/80">Grade {{ $student->classroom->grade_level }}</p>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-indigo-100/80 flex items-center justify-between text-xs text-indigo-900/80">
                            <span>Academic Session:</span>
                            <span class="font-mono font-semibold">{{ $student->classroom->academic_year }}</span>
                        </div>
                    </div>
                @else
                    <div class="p-3.5 rounded-2xl bg-amber-50/60 border border-amber-200/70 text-xs text-amber-800 space-y-1">
                        <p class="font-bold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Not Enrolled in Classroom
                        </p>
                        <p class="text-[11px] text-amber-700/90 leading-relaxed">
                            Assign this student to an active classroom in the form to register them for class attendance, schedules, and grading.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Sidebar Card 3: Quick Save Floating Sticky Action Card -->
            <div class="bg-indigo-900 rounded-3xl p-6 text-white shadow-xl shadow-indigo-950/20 space-y-4 lg:sticky lg:top-24">
                <div>
                    <h3 class="text-sm font-bold text-white">Save & Commit</h3>
                    <p class="text-xs text-indigo-200/80 mt-0.5">Update student record, registry status, and personal details.</p>
                </div>

                <div class="space-y-2">
                    <button 
                        type="submit" 
                        form="edit-student-form"
                        class="w-full py-2.5 px-4 rounded-xl bg-white hover:bg-indigo-50 text-indigo-900 font-bold text-xs shadow-md transition-all duration-150 flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span>Update Student Record</span>
                    </button>

                    <a 
                        href="{{ route('admin.students.index') }}" 
                        class="w-full py-2 px-4 rounded-xl text-center text-xs font-semibold text-indigo-200 hover:text-white hover:bg-white/10 transition-colors block"
                    >
                        Cancel & Return
                    </a>
                </div>

                <div class="pt-3 border-t border-indigo-800/80 text-[11px] text-indigo-300 flex items-center justify-between">
                    <span>Last Updated:</span>
                    <span class="font-medium text-white">{{ $student->updated_at ? $student->updated_at->diffForHumans() : 'Just now' }}</span>
                </div>
            </div>

            <!-- Sidebar Card 4: Danger Zone -->
            <div class="bg-rose-50/60 rounded-3xl p-6 border border-rose-100 shadow-2xs space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-rose-950">Danger Zone</h3>
                    <p class="text-xs text-rose-600/90 mt-0.5">Suspend access or permanently delete this student record</p>
                </div>

                <div class="space-y-3 pt-1">
                    <form 
                        action="{{ route('admin.students.toggle-status', $student->id) }}" 
                        method="POST" 
                        onsubmit="return confirm('{{ $isSuspended ? 'Reinstate this student account?' : 'Suspend this student account?' }}')"
                    >
                        @csrf
                        @method('PATCH')
                        <button 
                            type="submit" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs font-semibold transition-colors cursor-pointer {{ $isSuspended ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-amber-600 hover:bg-amber-700 text-white shadow-xs' }}"
                        >
                            {{ $isSuspended ? 'Reinstate Student Account' : 'Suspend Student Access' }}
                        </button>
                    </form>

                    <form 
                        action="{{ route('admin.students.destroy', $student->id) }}" 
                        method="POST" 
                        onsubmit="return confirm('Are you sure you want to permanently delete this student record? This action cannot be undone.')"
                    >
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white transition-colors cursor-pointer shadow-xs"
                        >
                            Delete Student Record
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
