@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header & Quick Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Academic Overview</h1>
            <p class="text-sm text-slate-500 mt-1">Welcome back, {{ auth()->user()->name ?? 'Administrator' }}! Here is what's happening in your school today.</p>
        </div>
        <div class="flex items-center gap-3">
            <a 
                href="{{ route('admin.students.create') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm hover:shadow-md transition-all duration-150 cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Student</span>
            </a>
            <button 
                type="button" 
                onclick="window.print()"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold border border-slate-200 shadow-2xs hover:border-slate-300 transition-colors cursor-pointer"
                title="Print / Save Academic Overview"
            >
                <svg class="w-4.5 h-4.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Export Report</span>
            </button>
        </div>
    </div>

    @include('admin.dashboard.stats-card')

    <!-- Main Section: Data Table & Quick Insights -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        @include('admin.dashboard.recent-students-table')

        <!-- Right Side Widget Column (1 Col) -->
        <div class="space-y-6">
            @include('admin.dashboard._sidebar-widgets')
        </div>
    </div>
</div>
@endsection
