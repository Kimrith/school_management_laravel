@extends('layouts.admin')

@section('title', 'Edit Classroom - ' . $classroom->name)
@section('page_title', 'Edit Classroom')

@section('content')
@php
    $isSuspended = ($classroom->status === 'suspended');
    $enrolled = $classroom->student_profiles_count ?? $classroom->studentProfiles()->count();
    $capacity = max(1, $classroom->capacity ?? 40);
    $percent = min(100, round(($enrolled / $capacity) * 100));
    $headTeacher = $classroom->teachers->first();
@endphp

<div class="w-full space-y-6">
    <!-- Header with Breadcrumbs & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-1">
                <a href="{{ route('admin.classes.index') }}" class="hover:text-indigo-600 transition-colors">Classrooms Directory</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Edit Classroom Details</span>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    {{ $classroom->name }}
                </h1>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $isSuspended ? 'bg-amber-50 text-amber-700 border border-amber-200/60' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isSuspended ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                    {{ $isSuspended ? 'Archived' : 'Active' }}
                </span>
                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                    {{ $classroom->level->name ?? $classroom->grade_level }}
                </span>
                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $classroom->academic_year }}
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Modify classroom identification, grade level, room allocation, capacity, and head teacher assignment.
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <a 
                href="{{ route('admin.classes.index') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-colors"
            >
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Back to Classes</span>
            </a>

            <button 
                type="submit" 
                form="edit-classroom-form"
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
            <form id="edit-classroom-form" action="{{ route('admin.classes.update', $classroom) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Card 1: Core Classroom Configuration -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs font-bold text-sm">
                                01
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 tracking-tight">Classroom Identification & Academic Year</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Define classroom section title, grade grouping, and curriculum session</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <!-- Classroom Name -->
                        <div class="sm:col-span-2">
                            <label for="name" class="block font-semibold text-slate-700 mb-1">Classroom Name *</label>
                            <input 
                                type="text" 
                                id="name"
                                name="name" 
                                value="{{ old('name', $classroom->name) }}"
                                required 
                                placeholder="e.g. Grade 10-A" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('name') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800 placeholder:text-slate-400"
                            >
                            @error('name')
                                <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Grade Level -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label for="grade_level" class="block font-semibold text-slate-700">Grade Level *</label>
                                <a href="{{ route('admin.levels.index') }}" target="_blank" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-700 hover:underline">
                                    + Manage Levels
                                </a>
                            </div>
                            <select 
                                id="grade_level"
                                name="grade_level" 
                                required 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('grade_level') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800"
                            >
                                @if(isset($levels) && $levels->isNotEmpty())
                                    @foreach($levels as $lvl)
                                        <option value="{{ $lvl->name }}" {{ old('grade_level', $classroom->level?->name ?? $classroom->grade_level) == $lvl->name ? 'selected' : '' }}>
                                            {{ $lvl->name }}
                                        </option>
                                    @endforeach
                                @else
                                    @foreach($gradeLevels ?? ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'] as $lvl)
                                        <option value="{{ $lvl }}" {{ old('grade_level', $classroom->grade_level) == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('grade_level')
                                <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Academic Year -->
                        <div>
                            <label for="academic_year" class="block font-semibold text-slate-700 mb-1">Academic Year *</label>
                            <input 
                                type="text" 
                                id="academic_year"
                                name="academic_year" 
                                value="{{ old('academic_year', $classroom->academic_year) }}"
                                required 
                                placeholder="e.g. 2025-2026" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('academic_year') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium text-slate-800 placeholder:text-slate-400"
                            >
                            @error('academic_year')
                                <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Room / Location -->
                        <div>
                            <label for="room" class="block font-semibold text-slate-700 mb-1">Physical Room / Hall</label>
                            <input 
                                type="text" 
                                id="room"
                                name="room" 
                                value="{{ old('room', $classroom->room) }}"
                                placeholder="e.g. Room 301, Building B" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800 placeholder:text-slate-400"
                            >
                        </div>

                        <!-- Student Capacity -->
                        <div>
                            <label for="capacity" class="block font-semibold text-slate-700 mb-1">Max Student Capacity</label>
                            <input 
                                type="number" 
                                id="capacity"
                                name="capacity" 
                                value="{{ old('capacity', $classroom->capacity ?? 40) }}"
                                min="1" 
                                max="200"
                                placeholder="40" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium text-slate-800 placeholder:text-slate-400"
                            >
                        </div>
                    </div>
                </div>

                <!-- Card 2: Faculty & Class Advisor -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center shrink-0 shadow-2xs font-bold text-sm">
                                02
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 tracking-tight">Faculty & Advisor Placement</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Assign head teacher overseeing classroom attendance and student welfare</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 text-xs">
                        <div>
                            <label for="teacher_id" class="block font-semibold text-slate-700 mb-1">Head Teacher / Advisor</label>
                            <select 
                                id="teacher_id"
                                name="teacher_id" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800"
                            >
                                <option value="">No head teacher assigned</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $headTeacher?->id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }} &bull; {{ $teacher->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Section Description / Equipment Notes -->
                        <div>
                            <label for="description" class="block font-semibold text-slate-700 mb-1">
                                Section Description & Facility Notes
                                <span class="text-xs font-normal text-slate-400 ml-1">(Optional)</span>
                            </label>
                            <textarea 
                                id="description"
                                name="description" 
                                rows="3" 
                                placeholder="Describe course specializations, room equipment, smart board facilities, or lab setup..." 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800 placeholder:text-slate-400 resize-none leading-relaxed"
                            >{{ old('description', $classroom->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right / Sidebar Column -->
        <div class="w-full lg:w-80 shrink-0 space-y-6">

            <!-- Card 1: Classroom Snapshot & Enrollment Meter -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xl shrink-0">
                        🏫
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $classroom->name }}</h4>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $classroom->room ?: 'No room set' }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 space-y-3 text-xs">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-slate-500 font-medium">Capacity Utilization:</span>
                            <span class="font-mono font-bold text-slate-800">{{ $enrolled }} / {{ $capacity }}</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div 
                                class="h-full rounded-full transition-all duration-500 {{ $percent >= 100 ? 'bg-rose-500' : ($percent >= 85 ? 'bg-amber-500' : 'bg-indigo-600') }}" 
                                style="width: {{ $percent }}%"
                            ></div>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5 text-right font-medium">
                            {{ $percent }}% enrolled &bull; {{ max(0, $capacity - $enrolled) }} seats open
                        </p>
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-slate-400 font-medium">Active Enrolled:</span>
                        <a href="{{ route('admin.students.index', ['classroom_id' => $classroom->id]) }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                            {{ $enrolled }} students &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Save & Quick Status Card (Sticky) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 space-y-4 lg:sticky lg:top-24">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Publishing Controls</h4>

                <div class="space-y-3">
                    <label for="status" class="block text-xs font-semibold text-slate-700">Classroom Status</label>
                    <select 
                        name="status" 
                        form="edit-classroom-form" 
                        class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-semibold text-slate-700"
                    >
                        <option value="active" {{ old('status', $classroom->status) !== 'suspended' ? 'selected' : '' }}>Active - In Session</option>
                        <option value="suspended" {{ old('status', $classroom->status) === 'suspended' ? 'selected' : '' }}>Archived / Suspended</option>
                    </select>

                    <button 
                        type="submit" 
                        form="edit-classroom-form" 
                        class="w-full py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs hover:shadow transition-all duration-150 flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span>Update Classroom</span>
                    </button>

                    <a 
                        href="{{ route('admin.classes.index') }}" 
                        class="w-full py-2.5 px-4 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold text-xs transition-colors flex items-center justify-center"
                    >
                        Cancel
                    </a>
                </div>
            </div>

            <!-- Card 3: Danger Zone -->
            <div class="bg-rose-50/50 rounded-3xl border border-rose-100 p-6 space-y-4">
                <div class="flex items-center gap-2.5 text-rose-700">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <h4 class="text-xs font-bold uppercase tracking-wider">Danger Zone</h4>
                </div>

                <p class="text-xs text-rose-600/80 leading-relaxed">
                    Archiving or deleting this classroom will automatically unenroll students and unlink teacher assignments.
                </p>

                <div class="pt-2 border-t border-rose-100 space-y-2">
                    <form action="{{ route('admin.classes.toggle-status', $classroom) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button 
                            type="submit" 
                            class="w-full py-2 px-3 rounded-xl border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-semibold transition-colors"
                        >
                            {{ $isSuspended ? 'Reinstate Classroom to Active' : 'Archive Classroom' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.classes.destroy', $classroom) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this classroom? All enrolled students will be unassigned.');">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="w-full py-2 px-3 rounded-xl border border-rose-300 bg-white hover:bg-rose-50 text-rose-600 text-xs font-semibold transition-colors cursor-pointer"
                        >
                            Delete Classroom Permanently
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
