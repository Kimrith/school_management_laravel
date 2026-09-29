@extends('layouts.admin')

@section('title', 'Edit Faculty - ' . ($teacher->user->name ?? 'Teacher'))
@section('page_title', 'Edit Teacher')

@section('content')
@php
    $subjects = $subjects ?? \App\Models\Subject::orderBy('name')->get();
    $existingSubjectNames = $subjects->pluck('name')->toArray();
    $rawSelected = old('specializations', old('specialization') 
        ? array_filter(array_map('trim', explode(',', old('specialization')))) 
        : ($teacher->specialization ? array_filter(array_map('trim', explode(',', $teacher->specialization))) : []));
    $initialSelected = array_values(array_filter($rawSelected, fn($name) => in_array($name, $existingSubjectNames, true)));

    $classrooms = $classrooms ?? \App\Models\Classroom::orderBy('name')->get();
    $currentClassroomIds = $teacher->taughtClassrooms->unique('id')->pluck('id')->toArray();
    $initialClassrooms = old('classrooms', old('classroom_ids', $currentClassroomIds));
    $initialClassrooms = array_values(array_map('intval', (array) $initialClassrooms));

    $statusVal = old('status', $teacher->user->status?->value ?? $teacher->user->status ?? 'active');
    $isSuspended = $statusVal === 'suspended';
@endphp

<div class="w-full space-y-6">
    <!-- Header with Breadcrumbs & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-1">
                <a href="{{ route('admin.teachers.index') }}" class="hover:text-indigo-600 transition-colors">Faculty Directory</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Edit Faculty Profile</span>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    {{ $teacher->user->name ?? 'Faculty Member' }}
                </h1>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $isSuspended ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isSuspended ? 'bg-rose-500' : 'bg-emerald-500' }}"></span>
                    {{ ucfirst($statusVal) }}
                </span>
                @if($teacher->qualification)
                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $teacher->qualification }}
                    </span>
                @endif
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Manage teaching credentials, assigned classrooms, department specializations, and access privileges.
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <a 
                href="{{ route('admin.teachers.index') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-colors"
            >
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Back to Directory</span>
            </a>

            <button 
                type="submit" 
                form="edit-teacher-form"
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
            <form id="edit-teacher-form" action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Card 1: Faculty Account & Access Credentials -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs font-bold text-sm">
                                01
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 tracking-tight">Faculty Credentials & System Access</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Account identity, portal login email, and authentication status</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Full Name *</label>
                            <input 
                                type="text" 
                                name="name" 
                                value="{{ old('name', $teacher->user->name ?? '') }}" 
                                required 
                                placeholder="e.g. Prof. Virak Meas"
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
                                value="{{ old('email', $teacher->user->email ?? '') }}" 
                                required 
                                placeholder="teacher@school.edu"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('email') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400"
                            >
                            @error('email')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1.5">Account Status *</label>
                            <select 
                                name="status" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('status') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-700"
                            >
                                <option value="active" {{ $statusVal === 'active' ? 'selected' : '' }}>Active (Full Faculty Portal Access)</option>
                                <option value="inactive" {{ $statusVal === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ $statusVal === 'suspended' ? 'selected' : '' }}>Suspended (Portal Login Blocked)</option>
                            </select>
                            @error('status')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Academic Department & Specialization -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs font-bold text-sm">
                                02
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 tracking-tight">Specialization & Academic Department</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Assigned teaching disciplines, curriculum subjects, and expertise</p>
                            </div>
                        </div>
                    </div>

                    <!-- Specialization / Academic Department (Multi-Select from DB) -->
                    <div 
                        x-data="{
                            open: false,
                            selected: {{ json_encode(array_values($initialSelected)) }},
                            search: '',
                            items: {{ json_encode($subjects->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'code' => $s->code])) }},
                            toggle(name) {
                                if (this.selected.includes(name)) {
                                    this.selected = this.selected.filter(item => item !== name);
                                } else {
                                    this.selected.push(name);
                                }
                            },
                            get filteredItems() {
                                if (!this.search.trim()) return this.items;
                                const q = this.search.toLowerCase();
                                return this.items.filter(i => i.name.toLowerCase().includes(q) || i.code.toLowerCase().includes(q));
                            }
                        }"
                        class="relative text-xs space-y-2"
                        @click.outside="open = false"
                    >
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block font-semibold text-slate-700">
                                Assigned Subjects / Departments
                                <span class="text-xs font-normal text-slate-400 ml-1">(Choose one or more courses taught)</span>
                            </label>
                            <span 
                                x-show="selected.length > 0" 
                                x-text="selected.length + ' assigned'"
                                class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100"
                            ></span>
                        </div>

                        <!-- Hidden Inputs for Form Submission -->
                        <template x-for="val in selected" :key="val">
                            <input type="hidden" name="specializations[]" :value="val">
                        </template>
                        <input type="hidden" name="specialization" :value="selected.join(', ')">

                        <!-- Dropdown Trigger Box -->
                        <div 
                            @click="open = !open" 
                            class="min-h-[46px] w-full px-3.5 py-2 bg-slate-50 border @error('specialization') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl cursor-pointer hover:bg-slate-100/60 transition flex items-center justify-between gap-2"
                        >
                            <div class="flex flex-wrap items-center gap-1.5 flex-1">
                                <template x-if="selected.length === 0">
                                    <span class="text-slate-400 font-normal text-xs sm:text-sm">Click to select specializations or departments...</span>
                                </template>
                                <template x-for="(item, idx) in selected" :key="idx">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        <span x-text="item"></span>
                                        <button 
                                            type="button" 
                                            @click.stop="toggle(item)"
                                            class="text-indigo-400 hover:text-indigo-700 focus:outline-none ml-0.5 cursor-pointer"
                                            title="Remove"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                            </div>
                            <svg 
                                class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" 
                                :class="{'rotate-180': open}" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <!-- Dropdown Menu -->
                        <div 
                            x-show="open" 
                            x-cloak 
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="absolute left-0 right-0 mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-3 space-y-2.5"
                        >
                            <!-- Search filter -->
                            <div class="relative">
                                <input 
                                    type="text" 
                                    x-model="search" 
                                    @click.stop 
                                    placeholder="Search department or subject code..." 
                                    class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                >
                                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>

                            <!-- List of departments/subjects from DB -->
                            <div class="max-h-52 overflow-y-auto divide-y divide-slate-100">
                                <template x-for="subj in filteredItems" :key="subj.id">
                                    <div 
                                        @click="toggle(subj.name)"
                                        class="px-2.5 py-2 hover:bg-slate-50 rounded-xl cursor-pointer flex items-center justify-between text-xs transition"
                                    >
                                        <div class="flex items-center gap-2">
                                            <div 
                                                class="w-4 h-4 rounded border flex items-center justify-center transition"
                                                :class="selected.includes(subj.name) ? 'bg-indigo-600 border-indigo-600 text-white' : 'border-slate-300 bg-white'"
                                            >
                                                <svg x-show="selected.includes(subj.name)" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="font-medium text-slate-700" x-text="subj.name"></span>
                                        </div>
                                        <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 font-semibold" x-text="subj.code"></span>
                                    </div>
                                </template>
                                <div x-show="filteredItems.length === 0" class="py-3 text-center text-xs text-slate-400">
                                    No matching department or subject found.
                                </div>
                            </div>
                        </div>

                        <!-- Quick-Pick Pills from DB -->
                        @if($subjects->isNotEmpty())
                            <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                                <span class="text-[11px] text-slate-400 font-medium mr-1">Quick pick:</span>
                                @foreach($subjects as $subj)
                                    <button 
                                        type="button" 
                                        @click="toggle('{{ addslashes($subj->name) }}')"
                                        class="text-[11px] font-medium px-2.5 py-1 rounded-lg border transition cursor-pointer"
                                        :class="selected.includes('{{ addslashes($subj->name) }}') ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100 hover:text-slate-800'"
                                    >
                                        + {{ $subj->name }} ({{ $subj->code }})
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        @error('specialization')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Card 3: Assigned Classrooms & Rooms (Multi-Class Assignment) -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs font-bold text-sm">
                                03
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 tracking-tight">Assigned Classes / Rooms</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Faculty member can be assigned to teach across multiple classes simultaneously</p>
                            </div>
                        </div>
                    </div>

                    <!-- Class Assignment Multi-Select from DB -->
                    <div 
                        x-data="{
                            open: false,
                            selected: {{ json_encode($initialClassrooms) }},
                            search: '',
                            items: {{ json_encode($classrooms->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'grade' => $c->grade_level, 'year' => $c->academic_year])) }},
                            toggle(id) {
                                id = parseInt(id);
                                if (this.selected.includes(id)) {
                                    this.selected = this.selected.filter(item => item !== id);
                                } else {
                                    this.selected.push(id);
                                }
                            },
                            get selectedObjects() {
                                return this.items.filter(i => this.selected.includes(i.id));
                            },
                            get filteredItems() {
                                if (!this.search.trim()) return this.items;
                                const q = this.search.toLowerCase();
                                return this.items.filter(i => i.name.toLowerCase().includes(q) || (i.grade && i.grade.toLowerCase().includes(q)));
                            }
                        }"
                        class="relative text-xs space-y-2"
                        @click.outside="open = false"
                    >
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block font-semibold text-slate-700">
                                Assigned Classroom Groups
                                <span class="text-xs font-normal text-slate-400 ml-1">(Select one or more classrooms)</span>
                            </label>
                            <span 
                                x-show="selected.length > 0" 
                                x-text="selected.length + ' class' + (selected.length > 1 ? 'es' : '') + ' assigned'"
                                class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100"
                            ></span>
                        </div>

                        <!-- Hidden Inputs for Form Submission -->
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="classrooms[]" :value="id">
                        </template>

                        <!-- Dropdown Trigger Box -->
                        <div 
                            @click="open = !open" 
                            class="min-h-[46px] w-full px-3.5 py-2 bg-slate-50 border @error('classrooms') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl cursor-pointer hover:bg-slate-100/60 transition flex items-center justify-between gap-2"
                        >
                            <div class="flex flex-wrap items-center gap-1.5 flex-1">
                                <template x-if="selected.length === 0">
                                    <span class="text-slate-400 font-normal text-xs sm:text-sm">Click to select classes to assign...</span>
                                </template>
                                <template x-for="item in selectedObjects" :key="item.id">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span x-text="item.name"></span>
                                        <button 
                                            type="button" 
                                            @click.stop="toggle(item.id)"
                                            class="text-indigo-400 hover:text-indigo-700 focus:outline-none ml-0.5 cursor-pointer"
                                            title="Remove"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                            </div>
                            <svg 
                                class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" 
                                :class="{'rotate-180': open}" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <!-- Dropdown Menu -->
                        <div 
                            x-show="open" 
                            x-cloak 
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="absolute left-0 right-0 mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-3 space-y-2.5"
                        >
                            <!-- Search filter -->
                            <div class="relative">
                                <input 
                                    type="text" 
                                    x-model="search" 
                                    @click.stop 
                                    placeholder="Search class or grade level..." 
                                    class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                >
                                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>

                            <!-- List of classrooms from DB -->
                            <div class="max-h-52 overflow-y-auto divide-y divide-slate-100">
                                <template x-for="cls in filteredItems" :key="cls.id">
                                    <div 
                                        @click="toggle(cls.id)"
                                        class="px-2.5 py-2 hover:bg-slate-50 rounded-xl cursor-pointer flex items-center justify-between text-xs transition"
                                    >
                                        <div class="flex items-center gap-2">
                                            <div 
                                                class="w-4 h-4 rounded border flex items-center justify-center transition"
                                                :class="selected.includes(cls.id) ? 'bg-indigo-600 border-indigo-600 text-white' : 'border-slate-300 bg-white'"
                                            >
                                                <svg x-show="selected.includes(cls.id)" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="font-medium text-slate-700" x-text="cls.name"></span>
                                        </div>
                                        <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 font-semibold" x-text="cls.grade"></span>
                                    </div>
                                </template>
                                <div x-show="filteredItems.length === 0" class="py-3 text-center text-xs text-slate-400">
                                    No matching class found.
                                </div>
                            </div>
                        </div>

                        <!-- Quick-Pick Class Pills from DB -->
                        @if($classrooms->isNotEmpty())
                            <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                                <span class="text-[11px] text-slate-400 font-medium mr-1">Quick assign:</span>
                                @foreach($classrooms as $cls)
                                    <button 
                                        type="button" 
                                        @click="toggle({{ $cls->id }})"
                                        class="text-[11px] font-medium px-2.5 py-1 rounded-lg border transition cursor-pointer"
                                        :class="selected.includes({{ $cls->id }}) ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100 hover:text-slate-800'"
                                    >
                                        + {{ $cls->name }}
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        @error('classrooms')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Card 4: Contact Information & Location -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs font-bold text-sm">
                                04
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 tracking-tight">Contact & Academic Credentials</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Degree title, phone reachability, and office location</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Contact Phone</label>
                            <input 
                                type="text" 
                                name="phone" 
                                value="{{ old('phone', $teacher->phone) }}" 
                                placeholder="012 345 678" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('phone') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium placeholder:text-slate-400"
                            >
                            @error('phone')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Degree / Highest Qualification</label>
                            <input 
                                type="text" 
                                name="qualification" 
                                value="{{ old('qualification', $teacher->qualification) }}" 
                                placeholder="e.g. Master of Computer Science" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('qualification') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400"
                            >
                            @error('qualification')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1.5">Office / Residential Address</label>
                            <textarea 
                                name="address" 
                                rows="3" 
                                placeholder="e.g. Building C, Faculty Room 204, Phnom Penh" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('address') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium resize-none placeholder:text-slate-400"
                            >{{ old('address', $teacher->address) }}</textarea>
                            @error('address')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Bottom Action Bar inside Form -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 flex items-center justify-between gap-4 shadow-xs">
                    <a 
                        href="{{ route('admin.teachers.index') }}" 
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
                            <span>Save Profile Changes</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right Column / Sidebar Panel -->
        <div class="w-full lg:w-80 xl:w-96 shrink-0 space-y-6">
            
            <!-- Sidebar Card 1: Faculty Identity Badge & Quick Stats -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-5">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-500 via-indigo-600 to-sky-400 text-white font-bold text-lg flex items-center justify-center shrink-0 shadow-md shadow-indigo-500/20 border-2 border-white ring-1 ring-slate-100">
                        {{ strtoupper(substr($teacher->user->name ?? 'TC', 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-base font-bold text-slate-900 truncate">{{ $teacher->user->name ?? 'Faculty Member' }}</h2>
                        <p class="text-xs text-slate-400 truncate">{{ $teacher->user->email ?? 'No email' }}</p>
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
                        <span class="block text-[11px] font-medium text-slate-400">Assigned Classes</span>
                        <span class="text-lg font-bold text-slate-900 mt-0.5 block">
                            {{ $teacher->taughtClassrooms->unique('id')->count() }}
                        </span>
                    </div>

                    <div class="p-3 bg-slate-50/80 rounded-2xl border border-slate-100">
                        <span class="block text-[11px] font-medium text-slate-400">Specializations</span>
                        <span class="text-lg font-bold text-slate-900 mt-0.5 block">
                            {{ $teacher->specialization ? count(array_filter(explode(',', $teacher->specialization))) : 0 }}
                        </span>
                    </div>
                </div>

                <!-- Details List -->
                <div class="space-y-2.5 pt-2 text-xs">
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="text-slate-400">Qualification</span>
                        <span class="font-medium text-slate-800 text-right truncate max-w-[170px]">{{ $teacher->qualification ?? 'Not set' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="text-slate-400">Contact Phone</span>
                        <span class="font-mono font-medium text-slate-800">{{ $teacher->phone ?? 'Not set' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="text-slate-400">Profile Created</span>
                        <span class="font-medium text-slate-800">{{ $teacher->created_at ? $teacher->created_at->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar Card 2: Assigned Classrooms Visual Preview -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Current Assigned Classes</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Live database associations</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold font-mono bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $teacher->taughtClassrooms->unique('id')->count() }}
                    </span>
                </div>

                <div class="space-y-2">
                    @forelse($teacher->taughtClassrooms->unique('id') as $classroom)
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100 hover:bg-indigo-50/50 hover:border-indigo-100 transition-colors">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800 leading-snug">{{ $classroom->name }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $classroom->grade_level }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-white text-slate-500 font-semibold border border-slate-200">
                                {{ $classroom->academic_year }}
                            </span>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-slate-400">
                            <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p class="font-medium text-slate-600">No classes assigned</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Use the multi-class selector to assign classrooms.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Sidebar Card 3: Quick Save Floating Sticky Action Card -->
            <div class="bg-indigo-900 rounded-3xl p-6 text-white shadow-xl shadow-indigo-950/20 space-y-4 lg:sticky lg:top-24">
                <div>
                    <h3 class="text-sm font-bold text-white">Save & Sync</h3>
                    <p class="text-xs text-indigo-200/80 mt-0.5">Commit modifications to credentials, assigned classes, and disciplines.</p>
                </div>

                <div class="space-y-2">
                    <button 
                        type="submit" 
                        form="edit-teacher-form"
                        class="w-full py-2.5 px-4 rounded-xl bg-white hover:bg-indigo-50 text-indigo-900 font-bold text-xs shadow-md transition-all duration-150 flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span>Update Faculty Profile</span>
                    </button>

                    <a 
                        href="{{ route('admin.teachers.index') }}" 
                        class="w-full py-2 px-4 rounded-xl text-center text-xs font-semibold text-indigo-200 hover:text-white hover:bg-white/10 transition-colors block"
                    >
                        Cancel & Return
                    </a>
                </div>

                <div class="pt-3 border-t border-indigo-800/80 text-[11px] text-indigo-300 flex items-center justify-between">
                    <span>Last Updated:</span>
                    <span class="font-medium text-white">{{ $teacher->updated_at ? $teacher->updated_at->diffForHumans() : 'Just now' }}</span>
                </div>
            </div>

            <!-- Sidebar Card 4: Danger Zone -->
            <div class="bg-rose-50/60 rounded-3xl p-6 border border-rose-100 shadow-2xs space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-rose-950">Danger Zone</h3>
                    <p class="text-xs text-rose-600/90 mt-0.5">Suspend access or permanently delete this faculty record</p>
                </div>

                <div class="space-y-3 pt-1">
                    <form 
                        action="{{ route('admin.teachers.toggle-status', $teacher->id) }}" 
                        method="POST" 
                        onsubmit="return confirm('{{ $isSuspended ? 'Reinstate this faculty member?' : 'Suspend this faculty member?' }}')"
                    >
                        @csrf
                        @method('PATCH')
                        <button 
                            type="submit" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs font-semibold transition-colors cursor-pointer {{ $isSuspended ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-amber-600 hover:bg-amber-700 text-white shadow-xs' }}"
                        >
                            {{ $isSuspended ? 'Reinstate Faculty Account' : 'Suspend Faculty Access' }}
                        </button>
                    </form>

                    <form 
                        action="{{ route('admin.teachers.destroy', $teacher->id) }}" 
                        method="POST" 
                        onsubmit="return confirm('Are you sure you want to permanently delete this faculty record? This action cannot be undone.')"
                    >
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white transition-colors cursor-pointer shadow-xs"
                        >
                            Delete Faculty Record
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
