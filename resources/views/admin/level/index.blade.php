@extends('layouts.admin')

@section('title', 'Academic Levels & Grades')
@section('page_title', 'Academic Levels')

@section('content')
<div 
    class="space-y-6" 
    x-data="{ 
        addModalOpen: {{ session('open_add_modal') || ($errors->any() && !old('_method')) ? 'true' : 'false' }}, 
        editModalOpen: false, 
        deleteModalOpen: false,
        selectedLevel: {
            id: '',
            name: '',
            status: 'Active'
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
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Levels</h1>
            <p class="text-sm text-slate-500 mt-1">Manage school education tiers, grade groupings, and curriculum stages.</p>
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
                <span>Add Level</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Levels -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Levels</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $counts['all'] ?? 0 }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">{{ $counts['active'] ?? 0 }} active in school</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xl">
                🏷️
            </div>
        </div>

        <!-- Active Levels -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Active Curriculum</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $counts['active'] ?? 0 }}</p>
                <p class="text-[11px] text-emerald-600 font-semibold mt-0.5">Ready for enrollment</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xl">
                ✓
            </div>
        </div>

        <!-- Suspended Tiers -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Suspended Tiers</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $counts['suspended'] ?? 0 }}</p>
                <a href="{{ route('admin.levels.suspended') }}" class="text-[11px] font-semibold text-amber-600 hover:text-amber-700 mt-0.5 inline-block">
                    View archived &rarr;
                </a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center font-bold text-xl">
                ⏸️
            </div>
        </div>

        <!-- Mapped Sections -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Classrooms</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $counts['total_classrooms'] ?? 0 }}</p>
                <a href="{{ route('admin.classes.index') }}" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-700 mt-0.5 inline-block">
                    View classes &rarr;
                </a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 border border-sky-100 text-sky-600 flex items-center justify-center font-bold text-xl">
                🏫
            </div>
        </div>
    </div>

    <!-- Status Tabs: All vs Active vs Suspended -->
    <div class="flex items-center gap-2 border-b border-slate-200/80">
        <a 
            href="{{ route('admin.levels.index', ['status' => 'all']) }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 font-medium text-sm transition-colors {{ ($statusFilter ?? 'all') === 'all' ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800' }}"
        >
            <span>All Levels</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold {{ ($statusFilter ?? 'all') === 'all' ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['all'] ?? 0 }}
            </span>
        </a>

        <a 
            href="{{ route('admin.levels.index', ['status' => 'active']) }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 font-medium text-sm transition-colors {{ ($statusFilter ?? '') === 'active' ? 'border-emerald-600 text-emerald-700 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800' }}"
        >
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Active Tiers</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold {{ ($statusFilter ?? '') === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['active'] ?? 0 }}
            </span>
        </a>

        <a 
            href="{{ route('admin.levels.suspended') }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 font-medium text-sm transition-colors border-transparent text-slate-500 hover:text-slate-800"
        >
            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            <span>Suspended Levels</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-slate-100 text-slate-600">
                {{ $counts['suspended'] ?? 0 }}
            </span>
        </a>
    </div>

    <!-- Search Toolbar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.levels.index') }}" method="GET" class="w-full sm:w-80">
            <input type="hidden" name="status" value="{{ $statusFilter ?? 'all' }}">

            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search by level name (e.g. Grade 10)..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                >
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>
        </form>

        <div class="flex items-center gap-2 text-xs text-slate-500">
            <span>Showing {{ $levels->total() }} academic tiers</span>
        </div>
    </div>

    <!-- Levels Grid (Table Component) -->
    @include('admin.level.table')

    <!-- Add Level Modal -->
    @include('admin.level.insert')

    <!-- Quick In-Place Edit Modal -->
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
                class="inline-block w-full max-w-md text-left bg-white rounded-3xl shadow-2xl border border-slate-200/80 transform transition-all relative z-50 overflow-hidden"
            >
                <div class="p-6 pb-4 border-b border-slate-100 flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center shrink-0 shadow-2xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 tracking-tight">Edit Academic Level</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Update stage classification and active curriculum status</p>
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

                <form :action="'/admin/levels/' + selectedLevel.id" method="POST" class="p-6 pt-4 space-y-4 text-xs">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Level Name *</label>
                        <input 
                            type="text" 
                            name="name" 
                            x-model="selectedLevel.name" 
                            required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800"
                        >
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Status *</label>
                        <select 
                            name="status" 
                            x-model="selectedLevel.status" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-semibold text-slate-800"
                        >
                            <option value="Active">Active - In Curriculum</option>
                            <option value="Suspended">Suspended / Inactive</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a 
                            :href="'/admin/levels/' + selectedLevel.id + '/edit'" 
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
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Delete Confirmation Modal -->
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
                <form :action="'/admin/levels/' + selectedLevel.id" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="p-6">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mb-4 shadow-2xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 tracking-tight">Delete Academic Level</h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Are you sure you want to delete <span class="font-bold text-slate-900" x-text="selectedLevel.name"></span>? 
                            This action removes the grade tier from the system.
                        </p>
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
