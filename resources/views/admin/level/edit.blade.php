@extends('layouts.admin')

@section('title', 'Edit Academic Level - ' . $level->name)
@section('page_title', 'Edit Level')

@section('content')
@php
    $isActive = strtolower($level->status) === 'active';
    $classroomsCount = $level->classrooms_count ?? $level->classrooms()->count();
    $classrooms = $level->classrooms()->withCount('studentProfiles')->get();
@endphp

<div class="w-full space-y-6">
    <!-- Header with Breadcrumbs & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-1">
                <a href="{{ route('admin.levels.index') }}" class="hover:text-indigo-600 transition-colors">Academic Levels</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Edit Level Details</span>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    {{ $level->name }}
                </h1>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $isActive ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                    {{ $isActive ? 'Active Tier' : 'Suspended' }}
                </span>
                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    #LVL-{{ str_pad($level->id, 3, '0', STR_PAD_LEFT) }}
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Modify level stage name, academic status, and review associated classroom sections.
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <a 
                href="{{ route('admin.levels.index') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-colors"
            >
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Back to Levels</span>
            </a>

            <button 
                type="submit" 
                form="edit-level-form"
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
            <form id="edit-level-form" action="{{ route('admin.levels.update', $level) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Card 1: Core Level Configuration -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs font-bold text-sm">
                                01
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 tracking-tight">Academic Level Configuration</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Specify grade grouping, educational tier, or year level title</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 text-xs">
                        <div>
                            <label for="level_name" class="block font-semibold text-slate-700 mb-1">Level Name *</label>
                            <input 
                                type="text" 
                                id="level_name"
                                name="name" 
                                value="{{ old('name', $level->name) }}"
                                required 
                                placeholder="e.g. Grade 10" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('name') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800"
                            >
                            @error('name')
                                <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status input for main form -->
                        <div>
                            <label for="level_status" class="block font-semibold text-slate-700 mb-1">Curriculum Status *</label>
                            <select 
                                id="level_status"
                                name="status" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-semibold text-slate-800"
                            >
                                <option value="Active" {{ old('status', $level->status) === 'Active' ? 'selected' : '' }}>Active - In Curriculum</option>
                                <option value="Suspended" {{ old('status', $level->status) === 'Suspended' ? 'selected' : '' }}>Suspended / Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Associated Classrooms & Sections -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center shrink-0 shadow-2xs font-bold text-sm">
                                02
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 tracking-tight">Classrooms in this Grade Level</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Physical sections and cohorts mapped to {{ $level->name }}</p>
                            </div>
                        </div>

                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                            {{ $classrooms->count() }} {{ $classrooms->count() === 1 ? 'Classroom' : 'Classrooms' }}
                        </span>
                    </div>

                    @if($classrooms->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            @foreach($classrooms as $cls)
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $cls->name }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono mt-0.5">
                                            {{ $cls->room ?: 'No room' }} &bull; {{ $cls->academic_year }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-white border border-slate-200 text-slate-700">
                                            {{ $cls->student_profiles_count ?? 0 }} Students
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 text-center text-xs text-slate-400">
                            <p>No classrooms currently linked to this level name.</p>
                            <a href="{{ route('admin.classes.index') }}" class="text-indigo-600 font-semibold hover:underline mt-1 inline-block">
                                Manage Classrooms &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <!-- Right / Sidebar Column -->
        <div class="w-full lg:w-80 shrink-0 space-y-6">

            <!-- Card 1: Level Snapshot -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 space-y-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xl shrink-0">
                        🏷️
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $level->name }}</h4>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">#LVL-{{ str_pad($level->id, 3, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium">Status:</span>
                        <span class="font-bold {{ $isActive ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $level->status }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium">Mapped Sections:</span>
                        <span class="font-bold text-slate-800">{{ $classroomsCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium">Created On:</span>
                        <span class="font-mono text-slate-600">{{ $level->created_at ? $level->created_at->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Sticky Save Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 space-y-4 lg:sticky lg:top-24">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Publishing Controls</h4>

                <div class="space-y-3">
                    <button 
                        type="submit" 
                        form="edit-level-form" 
                        class="w-full py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs hover:shadow transition-all duration-150 flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span>Update Level</span>
                    </button>

                    <a 
                        href="{{ route('admin.levels.index') }}" 
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.008v.008H12v-.008Z" />
                    </svg>
                    <h4 class="text-xs font-bold uppercase tracking-wider">Danger Zone</h4>
                </div>

                <p class="text-xs text-rose-600/80 leading-relaxed">
                    Suspending hides this level from class assignment dropdowns. Deleting removes the classification entirely.
                </p>

                <div class="pt-2 border-t border-rose-100 space-y-2">
                    <form action="{{ route('admin.levels.toggle-status', $level) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button 
                            type="submit" 
                            class="w-full py-2 px-3 rounded-xl border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-semibold transition-colors cursor-pointer"
                        >
                            {{ $isActive ? 'Suspend Level' : 'Reinstate Level to Active' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.levels.destroy', $level) }}" method="POST" onsubmit="return confirm('Permanently delete this academic level?');">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="w-full py-2 px-3 rounded-xl border border-rose-300 bg-white hover:bg-rose-50 text-rose-600 text-xs font-semibold transition-colors cursor-pointer"
                        >
                            Delete Level Permanently
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
