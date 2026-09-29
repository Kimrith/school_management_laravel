@extends('layouts.admin')

@section('title', 'Faculty Management')
@section('page_title', 'Teachers')

@section('content')
<div class="space-y-6" x-data="{ addModalOpen: {{ (request('create') || $errors->any()) ? 'true' : 'false' }} }">
    <!-- Header with Breadcrumbs & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Faculty Directory</h1>
            <p class="text-sm text-slate-500 mt-1">Manage teaching staff, qualifications, specializations, and departmental profiles.</p>
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
                <span>Add Teacher</span>
            </button>
        </div>
    </div>

    <!-- Status Tabs: All Statuses (All, Active, Inactive, Suspended) -->
    <div class="flex items-center gap-2 border-b border-slate-200/80 overflow-x-auto pb-px">
        <!-- All Teachers -->
        <a 
            href="{{ route('admin.teachers.index', array_merge(request()->except('page'), ['status' => 'all'])) }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors {{ ($statusFilter === 'all' || empty($statusFilter)) ? 'border-indigo-600 font-bold text-indigo-600' : 'border-transparent font-medium text-slate-500 hover:text-slate-800 hover:border-slate-300' }}"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            <span>All Faculty</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold font-mono {{ ($statusFilter === 'all' || empty($statusFilter)) ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['all'] ?? $teachers->total() }}
            </span>
        </a>

        <!-- Active Teachers -->
        <a 
            href="{{ route('admin.teachers.index', array_merge(request()->except('page'), ['status' => 'active'])) }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors {{ $statusFilter === 'active' ? 'border-emerald-600 font-bold text-emerald-600' : 'border-transparent font-medium text-slate-500 hover:text-slate-800 hover:border-slate-300' }}"
        >
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Active</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold font-mono {{ $statusFilter === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['active'] ?? 0 }}
            </span>
        </a>

        <!-- Inactive Teachers -->
        <a 
            href="{{ route('admin.teachers.index', array_merge(request()->except('page'), ['status' => 'inactive'])) }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors {{ $statusFilter === 'inactive' ? 'border-slate-700 font-bold text-slate-800' : 'border-transparent font-medium text-slate-500 hover:text-slate-800 hover:border-slate-300' }}"
        >
            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
            <span>Inactive</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold font-mono {{ $statusFilter === 'inactive' ? 'bg-slate-200 text-slate-800' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['inactive'] ?? 0 }}
            </span>
        </a>

        <!-- Suspended Teachers -->
        <a 
            href="{{ route('admin.teachers.index', array_merge(request()->except('page'), ['status' => 'suspended'])) }}" 
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
        <form method="GET" action="{{ route('admin.teachers.index') }}" class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <input type="hidden" name="status" value="{{ $statusFilter }}">

            <!-- Search bar -->
            <div class="relative w-full sm:w-80">
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, email, qualification..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                >
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700 transition-colors">
                Filter
            </button>

            @if(request('search') || (request('status') && request('status') !== 'all'))
                <a href="{{ route('admin.teachers.index') }}" class="text-xs text-rose-600 hover:underline">
                    Clear
                </a>
            @endif
        </form>

        <div class="flex items-center gap-2 text-xs text-slate-500 self-end md:self-auto">
            <span>Showing <span class="font-semibold text-slate-800">{{ $teachers->count() }}</span> of <span class="font-semibold text-slate-800">{{ $teachers->total() }}</span> Faculty Members</span>
        </div>
    </div>

    <!-- Teachers Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <th scope="col" class="py-3.5 pl-6 pr-3">Instructor</th>
                        <th scope="col" class="py-3.5 px-3">Qualification</th>
                        <th scope="col" class="py-3.5 px-3">Specialization</th>
                        <th scope="col" class="py-3.5 px-3">Class</th>
                        <th scope="col" class="py-3.5 px-3">Contact</th>
                        <th scope="col" class="py-3.5 px-3">Status</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Actions</th>
                    </tr>
                </thead>

                @include('admin.teachers.query')
            </table>
        </div>

        <!-- Pagination Footer -->
        @include('share.pagination', ['paginator' => $teachers])
    </div>

    <!-- Include Add Teacher Popup Form Modal -->
    @include('admin.teachers.insert')
</div>
@endsection
