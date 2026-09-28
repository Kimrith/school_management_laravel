@extends('layouts.admin')

@section('title', 'Edit Faculty - ' . ($teacher->user->name ?? 'Teacher'))
@section('page_title', 'Edit Teacher')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header with Breadcrumbs & Back Link -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-1">
                <a href="{{ route('admin.teachers.index') }}" class="hover:text-indigo-600 transition-colors">Faculty Directory</a>
                <span>/</span>
                <span class="text-slate-700">Edit Profile</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Faculty Profile</h1>
            <p class="text-xs text-slate-500 mt-0.5">Modify instructor credentials, departmental specialization, and contact info.</p>
        </div>

        <div class="flex items-center gap-3">
            <a 
                href="{{ route('admin.teachers.index') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-colors"
            >
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Back to Directory</span>
            </a>
        </div>
    </div>

    <!-- Teacher Quick Identity Badge -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <!-- Avatar -->
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 font-bold text-sm flex items-center justify-center shrink-0 border border-indigo-100 shadow-2xs">
                {{ strtoupper(substr($teacher->user->name ?? 'TC', 0, 2)) }}
            </div>
            
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-base font-bold text-slate-900">{{ $teacher->user->name ?? 'Faculty Member' }}</h2>
                    @if($teacher->qualification)
                        <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                            {{ $teacher->qualification }}
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $teacher->user->email ?? '' }} &bull; Department: <span class="font-medium text-slate-700">{{ $teacher->specialization ?? 'General' }}</span>
                </p>
            </div>
        </div>

        <div>
            @php
                $statusVal = $teacher->user->status?->value ?? $teacher->user->status ?? 'active';
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
        <form action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Faculty Credentials & Status -->
            <div class="space-y-4">
                <div class="border-b border-slate-100 pb-2">
                    <h3 class="text-sm font-bold text-slate-900">1. Faculty Credentials & Account Status</h3>
                    <p class="text-xs text-slate-400">Account login credentials and system access permissions</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Full Name *</label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name', $teacher->user->name ?? '') }}" 
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
                            value="{{ old('email', $teacher->user->email ?? '') }}" 
                            required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium"
                        >
                        @error('email')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1.5">Account Status *</label>
                        <select 
                            name="status" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium"
                        >
                            <option value="active" {{ old('status', $statusVal) === 'active' ? 'selected' : '' }}>Active (Full Access)</option>
                            <option value="inactive" {{ old('status', $statusVal) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $statusVal) === 'suspended' ? 'selected' : '' }}>Suspended (Login Blocked)</option>
                        </select>
                        @error('status')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Professional & Academic Profile -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div class="border-b border-slate-100 pb-2">
                    <h3 class="text-sm font-bold text-slate-900">2. Professional & Academic Profile</h3>
                    <p class="text-xs text-slate-400">Departmental specialization, degree, and contact information</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Contact Phone</label>
                        <input 
                            type="text" 
                            name="phone" 
                            value="{{ old('phone', $teacher->phone) }}" 
                            placeholder="012 345 678" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium"
                        >
                        @error('phone')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Degree / Qualification</label>
                        <input 
                            type="text" 
                            name="qualification" 
                            value="{{ old('qualification', $teacher->qualification) }}" 
                            placeholder="e.g. Master of Computer Science" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium"
                        >
                        @error('qualification')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Specialization / Academic Department (Multi-Select from DB) -->
                    @php
                        $subjects = $subjects ?? \App\Models\Subject::orderBy('name')->get();
                        $initialSelected = old('specializations', old('specialization') 
                            ? array_filter(array_map('trim', explode(',', old('specialization')))) 
                            : ($teacher->specialization ? array_filter(array_map('trim', explode(',', $teacher->specialization))) : []));
                    @endphp
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
                        class="sm:col-span-2 relative"
                        @click.outside="open = false"
                    >
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block font-semibold text-slate-700">
                                Specialization / Academic Department
                                <span class="text-xs font-normal text-slate-400 ml-1">(Assign one or more from curriculum)</span>
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
                            class="min-h-[44px] w-full px-3 py-2 bg-slate-50 border @error('specialization') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl cursor-pointer hover:bg-slate-100/60 transition flex items-center justify-between gap-2"
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
                            class="absolute left-0 right-0 mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-2.5 space-y-2"
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
                            <div class="max-h-48 overflow-y-auto divide-y divide-slate-100">
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
                            <div class="mt-2 flex flex-wrap items-center gap-1.5">
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

                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1.5">Office / Residential Address</label>
                        <textarea 
                            name="address" 
                            rows="2" 
                            placeholder="e.g. Building C, Room 204, Phnom Penh" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium resize-none"
                        >{{ old('address', $teacher->address) }}</textarea>
                        @error('address')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Bar -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <a 
                    href="{{ route('admin.teachers.index') }}" 
                    class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-50 font-semibold text-xs transition-colors"
                >
                    Cancel
                </a>

                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs hover:shadow transition-all duration-150 cursor-pointer"
                >
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone Card -->
    <div class="bg-rose-50/50 rounded-3xl p-6 border border-rose-100 shadow-2xs space-y-4">
        <div>
            <h3 class="text-sm font-bold text-rose-950">Danger Zone</h3>
            <p class="text-xs text-rose-600/90 mt-0.5">Suspend access or permanently delete this faculty record</p>
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pt-2">
            <div>
                <p class="text-xs font-semibold text-slate-800">
                    {{ $isSuspended ? 'Reinstate Faculty Member' : 'Suspend Faculty Access' }}
                </p>
                <p class="text-[11px] text-slate-500">
                    {{ $isSuspended ? 'Restore login access and re-activate profile.' : 'Immediately blocks faculty login and portal features.' }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <form 
                    action="{{ route('admin.teachers.toggle-status', $teacher->id) }}" 
                    method="POST" 
                    onsubmit="return confirm('{{ $isSuspended ? 'Reinstate this faculty member?' : 'Suspend this faculty member?' }}')"
                >
                    @csrf
                    @method('PATCH')
                    <button 
                        type="submit" 
                        class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors cursor-pointer {{ $isSuspended ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-amber-600 hover:bg-amber-700 text-white' }}"
                    >
                        {{ $isSuspended ? 'Reinstate Account' : 'Suspend Account' }}
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
                        class="px-4 py-2 rounded-xl text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white transition-colors cursor-pointer"
                    >
                        Delete Faculty
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
