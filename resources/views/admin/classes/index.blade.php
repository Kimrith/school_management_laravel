@extends('layouts.admin')

@section('title', 'Classrooms & Grades')
@section('page_title', 'Classes')

@section('content')
<div 
    class="space-y-6" 
    x-data="{ 
        addModalOpen: {{ session('open_add_modal') || ($errors->any() && !old('_method')) ? 'true' : 'false' }}, 
        editModalOpen: false, 
        deleteModalOpen: false,
        selectedClassroom: {
            id: '',
            name: '',
            level_id: '',
            grade_level: '',
            academic_year: '',
            room: '',
            capacity: 40,
            status: 'active',
            description: '',
            teacher_id: ''
        }
    }"
>
    <!-- Success Flash Alert -->
    @if(session('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
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
                </div>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 p-1 rounded-lg cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Header with Breadcrumbs & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Classrooms & Grades</h1>
            <p class="text-sm text-slate-500 mt-1">Manage school sections, physical room allocations, student capacities, and faculty advisors.</p>
        </div>
        <div class="flex items-center gap-3">
            <button 
                @click="addModalOpen = true"
                type="button" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-xs hover:shadow transition-all duration-150 cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Classroom</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Classrooms -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Classrooms</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $counts['all'] ?? 0 }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">{{ $counts['active'] ?? 0 }} active in session</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xl">
                🏫
            </div>
        </div>

        <!-- Enrolled Students -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Enrolled Students</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $counts['total_students'] ?? 0 }}</p>
                <p class="text-[11px] text-emerald-600 font-semibold mt-0.5">Assigned to sections</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xl">
                👨‍🎓
            </div>
        </div>

        <!-- Capacity Utilization -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Capacity Utilization</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $counts['utilization'] ?? 0 }}%</p>
                <div class="w-24 h-1.5 rounded-full bg-slate-100 mt-1.5 overflow-hidden">
                    <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $counts['utilization'] ?? 0 }}%"></div>
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 border border-sky-100 text-sky-600 flex items-center justify-center font-bold text-xl">
                📊
            </div>
        </div>

        <!-- Archived Classrooms -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Archived / Inactive</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $counts['suspended'] ?? 0 }}</p>
                <a href="{{ route('admin.classes.suspended') }}" class="text-[11px] font-semibold text-amber-600 hover:text-amber-700 mt-0.5 inline-block">
                    View archived &rarr;
                </a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center font-bold text-xl">
                📦
            </div>
        </div>
    </div>

    <!-- Status Tabs: All vs Active vs Archived -->
    <div class="flex items-center gap-2 border-b border-slate-200/80">
        <a 
            href="{{ route('admin.classes.index', ['status' => 'all']) }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 font-medium text-sm transition-colors {{ ($statusFilter ?? 'all') === 'all' ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800' }}"
        >
            <span>All Classrooms</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold {{ ($statusFilter ?? 'all') === 'all' ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['all'] ?? 0 }}
            </span>
        </a>

        <a 
            href="{{ route('admin.classes.index', ['status' => 'active']) }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 font-medium text-sm transition-colors {{ ($statusFilter ?? '') === 'active' ? 'border-emerald-600 text-emerald-700 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800' }}"
        >
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Active in Session</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold {{ ($statusFilter ?? '') === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['active'] ?? 0 }}
            </span>
        </a>

        <a 
            href="{{ route('admin.classes.suspended') }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 font-medium text-sm transition-colors border-transparent text-slate-500 hover:text-slate-800"
        >
            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            <span>Archived Classes</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-slate-100 text-slate-600">
                {{ $counts['suspended'] ?? 0 }}
            </span>
        </a>
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.classes.index') }}" method="GET" class="w-full sm:w-auto flex-1 flex flex-col sm:flex-row items-center gap-3">
            <input type="hidden" name="status" value="{{ $statusFilter ?? 'all' }}">

            <!-- Search input -->
            <div class="relative w-full sm:w-72">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search by class name, grade, or room..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                >
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>

            <!-- Grade Level Filter -->
            <div class="w-full sm:w-44">
                <select 
                    name="grade" 
                    onchange="this.form.submit()" 
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-medium"
                >
                    <option value="all">All Grade Levels</option>
                    @foreach($gradeLevels ?? ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'] as $lvl)
                        <option value="{{ $lvl }}" {{ request('grade') == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="hidden sm:inline-flex px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors cursor-pointer">
                Filter
            </button>

            @if(request('search') || request('grade'))
                <a href="{{ route('admin.classes.index', ['status' => $statusFilter ?? 'all']) }}" class="text-xs text-rose-600 hover:underline">
                    Clear
                </a>
            @endif
        </form>

        <div class="flex items-center gap-2 text-xs text-slate-500 self-end sm:self-auto shrink-0">
            <span>Showing {{ $classrooms->total() }} sections</span>
        </div>
    </div>

    <!-- Classrooms Grid (Table Component) -->
    @include('admin.classes.table')

    <!-- Add Classroom Modal -->
    @include('admin.classes.insert')

    <!-- In-Place Quick Edit Modal -->
    <div 
        x-show="editModalOpen" 
        x-cloak 
        @keydown.escape.window="editModalOpen = false"
        class="fixed inset-0 z-50 overflow-y-auto"
        role="dialog"
        aria-modal="true"
    >
        <div class="min-h-screen px-4 text-center flex items-center justify-center py-6 sm:py-10">
            <div 
                x-show="editModalOpen" 
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="editModalOpen = false" 
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
            ></div>

            <div 
                x-show="editModalOpen" 
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.stop
                class="inline-block w-full max-w-xl text-left bg-white rounded-3xl shadow-2xl border border-slate-200/80 transform transition-all relative z-50 overflow-hidden"
            >
                <div class="p-6 pb-4 border-b border-slate-100 flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center shrink-0 shadow-2xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Edit Classroom Details</h3>
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-mono font-bold bg-indigo-50 text-indigo-700" x-text="selectedClassroom.grade_level"></span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Quick update section title, room, capacity, and status</p>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        @click="editModalOpen = false" 
                        class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form :action="'/admin/classes/' + selectedClassroom.id" method="POST" class="p-6 pt-4 space-y-4 text-xs">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div class="sm:col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1">Classroom Name *</label>
                            <input 
                                type="text" 
                                name="name" 
                                x-model="selectedClassroom.name" 
                                required 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800"
                            >
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block font-semibold text-slate-700">Grade Level *</label>
                                <a href="{{ route('admin.levels.index') }}" target="_blank" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-700 hover:underline">
                                    + Manage
                                </a>
                            </div>
                            <select 
                                name="grade_level" 
                                x-model="selectedClassroom.grade_level" 
                                required 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800"
                            >
                                @if(isset($levels) && $levels->isNotEmpty())
                                    @foreach($levels as $lvl)
                                        <option value="{{ $lvl->name }}">{{ $lvl->name }}</option>
                                    @endforeach
                                @else
                                    @foreach($gradeLevels ?? ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'] as $lvl)
                                        <option value="{{ $lvl }}">{{ $lvl }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Academic Year *</label>
                            <input 
                                type="text" 
                                name="academic_year" 
                                x-model="selectedClassroom.academic_year" 
                                required 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium text-slate-800"
                            >
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Room / Hall</label>
                            <input 
                                type="text" 
                                name="room" 
                                x-model="selectedClassroom.room" 
                                placeholder="e.g. Room 301" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800"
                            >
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Student Capacity</label>
                            <input 
                                type="number" 
                                name="capacity" 
                                x-model="selectedClassroom.capacity" 
                                min="1" 
                                max="200" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium text-slate-800"
                            >
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1">Status</label>
                            <select 
                                name="status" 
                                x-model="selectedClassroom.status" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800"
                            >
                                <option value="active">Active - In Session</option>
                                <option value="suspended">Archived / Suspended</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a 
                            :href="'/admin/classes/' + selectedClassroom.id + '/edit'" 
                            class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold"
                        >
                            Open Full Editor &rarr;
                        </a>

                        <div class="flex items-center gap-3">
                            <button 
                                type="button" 
                                @click="editModalOpen = false" 
                                class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition-colors cursor-pointer"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit" 
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-xs transition-all cursor-pointer"
                            >
                                <span>Update Classroom</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div 
        x-show="deleteModalOpen" 
        x-cloak 
        @keydown.escape.window="deleteModalOpen = false"
        class="fixed inset-0 z-50 overflow-y-auto"
        role="dialog"
        aria-modal="true"
    >
        <div class="min-h-screen px-4 text-center flex items-center justify-center py-6 sm:py-10">
            <div 
                x-show="deleteModalOpen" 
                @click="deleteModalOpen = false" 
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
            ></div>

            <div 
                x-show="deleteModalOpen" 
                @click.stop
                class="inline-block w-full max-w-md text-left bg-white rounded-3xl shadow-2xl border border-slate-200/80 transform transition-all relative z-50 overflow-hidden"
            >
                <form :action="'/admin/classes/' + selectedClassroom.id" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="p-6">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mb-4 shadow-2xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 tracking-tight">Delete Classroom</h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Are you sure you want to delete <span class="font-bold text-slate-900" x-text="selectedClassroom.name"></span>? 
                            All enrolled students will have their classroom assignment cleared.
                        </p>

                        <div class="mt-4 p-3 bg-rose-50/70 border border-rose-100 rounded-xl flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.008v.008H12v-.008Z" />
                            </svg>
                            <p class="text-[11px] text-rose-800 leading-normal">
                                This action cannot be undone. Any active classroom sessions, teacher links, and attendance entries tied to this section will be affected.
                            </p>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button 
                            type="button" 
                            @click="deleteModalOpen = false" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 font-semibold text-xs transition-colors cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-xs transition-all cursor-pointer"
                        >
                            Confirm Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
