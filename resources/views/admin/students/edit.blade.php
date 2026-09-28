@extends('layouts.admin')

@section('title', 'Edit Student - ' . $student->student_code)
@section('page_title', 'Edit Student')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header with Breadcrumbs & Back Link -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-1">
                <a href="{{ route('admin.students.index') }}" class="hover:text-indigo-600 transition-colors">Students Directory</a>
                <span>/</span>
                <span class="text-slate-700">Edit Profile</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Student Record</h1>
            <p class="text-xs text-slate-500 mt-0.5">Modify student credentials, classroom assignment, and contact info.</p>
        </div>

        <div class="flex items-center gap-3">
            <a 
                href="{{ route('admin.students.index') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-colors"
            >
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Back to List</span>
            </a>
        </div>
    </div>

    <!-- Student Quick Identity Badge -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
        <!-- Avatar -->
        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 font-bold text-sm flex items-center justify-center shrink-0 border border-indigo-100">
            {{ strtoupper(substr($student->user->name ?? 'ST', 0, 2)) }}
        </div>
        
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-base font-bold text-slate-900">{{ $student->user->name ?? 'Student' }}</h2>
                <span class="px-2 py-0.5 rounded-md text-[11px] font-mono font-medium bg-slate-100 text-slate-600 border border-slate-200">
                    {{ $student->student_code }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ $student->user->email ?? '' }} &bull; Enrolled in <span class="font-medium text-slate-700">{{ $student->classroom->name ?? 'Unassigned' }}</span>
            </p>
        </div>
    </div>

    <div>
        @php
            $statusVal = $student->user->status?->value ?? $student->user->status ?? 'active';
            $isSuspended = $statusVal === 'suspended';
        @endphp
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $isSuspended ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $isSuspended ? 'bg-rose-500' : 'bg-emerald-500' }}"></span>
            {{ ucfirst($statusVal) }}
        </span>
    </div>
</div>

    <!-- Main Edit Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <form action="{{ route('admin.students.update', $student->id) }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Academic & Login Info -->
            <div class="space-y-4">
                <div class="border-b border-slate-100 pb-2">
                    <h3 class="text-sm font-bold text-slate-900">1. Student Credentials & Academic Placement</h3>
                    <p class="text-xs text-slate-400">Account login and classroom registry</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Full Student Name *</label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name', $student->user->name ?? '') }}" 
                            required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium"
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
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium"
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
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium"
                        >
                        @error('student_code')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Classroom Assignment</label>
                        <select 
                            name="classroom_id" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium"
                        >
                            <option value="">Unassigned</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ old('classroom_id', $student->classroom_id) == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }} (Grade {{ $classroom->grade_level }})
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
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium"
                        >
                            <option value="active" {{ old('status', $statusVal) === 'active' ? 'selected' : '' }}>Active (Full Access)</option>
                            <option value="suspended" {{ old('status', $statusVal) === 'suspended' ? 'selected' : '' }}>Suspended (Login Blocked)</option>
                            <option value="inactive" {{ old('status', $statusVal) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="pending" {{ old('status', $statusVal) === 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Gender *</label>
                        <select 
                            name="gender" 
                            required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium"
                        >
                            <option value="male" {{ old('gender', strtolower($student->gender)) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', strtolower($student->gender)) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', strtolower($student->gender)) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Civil & Contact Information -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div class="border-b border-slate-100 pb-2">
                    <h3 class="text-sm font-bold text-slate-900">2. Demographics & Family Contact</h3>
                    <p class="text-xs text-slate-400">Date of birth, guardian details, and home address</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Date of Birth</label>
                        <input 
                            type="date" 
                            name="date_of_birth" 
                            value="{{ old('date_of_birth', $student->date_of_birth ? \Illuminate\Support\Carbon::parse($student->date_of_birth)->format('Y-m-d') : '') }}" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium"
                        >
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Parent / Guardian Name</label>
                        <input 
                            type="text" 
                            name="parent_name" 
                            value="{{ old('parent_name', $student->parent_name) }}" 
                            placeholder="e.g. Chan Dara" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium"
                        >
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Parent Phone Number</label>
                        <input 
                            type="text" 
                            name="parent_phone" 
                            value="{{ old('parent_phone', $student->parent_phone) }}" 
                            placeholder="012 345 678" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium"
                        >
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Residential Home Address</label>
                        <textarea 
                            name="address" 
                            rows="2" 
                            placeholder="e.g. Phnom Penh, Cambodia" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium"
                        >{{ old('address', $student->address) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                <a 
                    href="{{ route('admin.students.index') }}" 
                    class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors"
                >
                    Cancel
                </a>

                <div class="flex items-center gap-3">
                    <button 
                        type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-md shadow-indigo-600/20 transition-all cursor-pointer"
                    >
                        Save Student Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
