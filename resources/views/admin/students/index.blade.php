@extends('layouts.admin')

@section('title', 'Students Management')
@section('page_title', 'Students')

@section('content')
<div class="space-y-6" x-data="{ addModalOpen: {{ (request('create') || $errors->any()) ? 'true' : 'false' }} }">
    <!-- Header with Breadcrumbs & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Students Directory</h1>
            <p class="text-sm text-slate-500 mt-1">Manage student profiles, enrollments, and academic placements.</p>
        </div>
        <div class="flex items-center gap-3">
            <button 
                type="button" 
                @click="addModalOpen = true"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm hover:shadow-md transition-all duration-150 cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Student</span>
            </button>
        </div>
    </div>

    <!-- Status Tabs: All Statuses (All, Active, Pending, Inactive, Suspended) -->
    <div class="flex items-center gap-2 border-b border-slate-200/80 overflow-x-auto pb-px">
        <!-- All Students -->
        <a 
            href="{{ route('admin.students.index', array_merge(request()->except('page'), ['status' => 'all'])) }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors {{ ($statusFilter === 'all' || empty($statusFilter)) ? 'border-indigo-600 font-bold text-indigo-600' : 'border-transparent font-medium text-slate-500 hover:text-slate-800 hover:border-slate-300' }}"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
            <span>All Students</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold font-mono {{ ($statusFilter === 'all' || empty($statusFilter)) ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['all'] ?? $students->total() }}
            </span>
        </a>

        <!-- Active Students -->
        <a 
            href="{{ route('admin.students.index', array_merge(request()->except('page'), ['status' => 'active'])) }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors {{ $statusFilter === 'active' ? 'border-emerald-600 font-bold text-emerald-600' : 'border-transparent font-medium text-slate-500 hover:text-slate-800 hover:border-slate-300' }}"
        >
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Active</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold font-mono {{ $statusFilter === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['active'] ?? 0 }}
            </span>
        </a>

        <!-- Pending Students -->
        <a 
            href="{{ route('admin.students.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors {{ $statusFilter === 'pending' ? 'border-amber-600 font-bold text-amber-600' : 'border-transparent font-medium text-slate-500 hover:text-slate-800 hover:border-slate-300' }}"
        >
            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            <span>Pending</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold font-mono {{ $statusFilter === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['pending'] ?? 0 }}
            </span>
        </a>

        <!-- Inactive Students -->
        <a 
            href="{{ route('admin.students.index', array_merge(request()->except('page'), ['status' => 'inactive'])) }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors {{ $statusFilter === 'inactive' ? 'border-slate-700 font-bold text-slate-800' : 'border-transparent font-medium text-slate-500 hover:text-slate-800 hover:border-slate-300' }}"
        >
            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
            <span>Inactive</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold font-mono {{ $statusFilter === 'inactive' ? 'bg-slate-200 text-slate-800' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['inactive'] ?? 0 }}
            </span>
        </a>

        <!-- Suspended Students -->
        <a 
            href="{{ route('admin.students.index', array_merge(request()->except('page'), ['status' => 'suspended'])) }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors {{ $statusFilter === 'suspended' ? 'border-rose-600 font-bold text-rose-600' : 'border-transparent font-medium text-slate-500 hover:text-slate-800 hover:border-slate-300' }}"
        >
            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            <span>Suspended</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold font-mono {{ $statusFilter === 'suspended' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['suspended'] ?? 0 }}
            </span>
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.students.index') }}" class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <input type="hidden" name="status" value="{{ $statusFilter }}">

            <!-- Search bar -->
            <div class="relative w-full sm:w-72">
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by code, name, phone..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                >
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>

            <!-- Classroom Filter -->
            <div class="w-full sm:w-56">
                <select 
                    name="classroom_id" 
                    onchange="this.form.submit()"
                    class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none"
                >
                    <option value="">All Classrooms</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }} (Grade {{ $classroom->grade_level }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700 transition-colors">
                Filter
            </button>

            @if(request('search') || request('classroom_id') || (request('status') && request('status') !== 'all'))
                <a href="{{ route('admin.students.index') }}" class="text-xs text-rose-600 hover:underline">
                    Clear
                </a>
            @endif
        </form>

        <div class="flex items-center gap-2 text-xs text-slate-500 self-end md:self-auto">
            <span>Showing <span class="font-semibold text-slate-800">{{ $students->count() }}</span> of <span class="font-semibold text-slate-800">{{ $students->total() }}</span> students</span>
        </div>
    </div>

    <!-- Students Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <th scope="col" class="py-3.5 pl-6 pr-3">Student Code</th>
                        <th scope="col" class="py-3.5 px-3">Student Name</th>
                        <th scope="col" class="py-3.5 px-3">Classroom</th>
                        <th scope="col" class="py-3.5 px-3">Level</th>
                        <th scope="col" class="py-3.5 px-3">Gender</th>
                        <th scope="col" class="py-3.5 px-3">Parent Contact</th>
                        <th scope="col" class="py-3.5 px-3">Status</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Actions</th>
                    </tr>
                </thead>

                @include('admin.students.table')
            </table>
        </div>

        <!-- Pagination Footer -->
        @include('share.pagination', ['paginator' => $students])
    </div>

    <!-- Include Add Student Popup Form Modal -->
    @include('admin.students.insert')
</div>
@endsection
