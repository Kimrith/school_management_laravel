@extends('layouts.admin')

@section('title', 'Student Scores & Academic Reports')
@section('page_title', 'Student Scores & Reports')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Student Scores & Examination Reports</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Live Teacher Submissions
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-1">
                Monitor student marks submitted by teachers, track academic performance across classrooms, and audit score sheets.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <button 
                type="button" 
                onclick="window.print()" 
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold shadow-2xs transition-colors cursor-pointer"
            >
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.07-.641-2.07-1.171-3.001M17.28 13.829c.24-1.07.641-2.07 1.171-3.001M9 17.25h6m-6 3h6m2.25-16.5H6.75A2.25 2.25 0 0 0 4.5 6v12a2.25 2.25 0 0 0 2.25 2.25h10.5A2.25 2.25 0 0 0 19.5 18V6a2.25 2.25 0 0 0-2.25-2.25Z" />
                </svg>
                <span>Print Score Sheet</span>
            </button>
        </div>
    </div>

    <!-- Analytics KPI Cards (Modular Partial) -->
    @include('admin.reports.stats')

    <!-- Filters & Student Score Records Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <!-- Filter Toolbar (Modular Partial) -->
        @include('admin.reports.filter')

        <!-- Scores Data Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <th scope="col" class="py-3.5 pl-6 pr-3">Student</th>
                        <th scope="col" class="py-3.5 px-3">Classroom</th>
                        <th scope="col" class="py-3.5 px-3">Examination & Subject</th>
                        <th scope="col" class="py-3.5 px-3">Exam Date</th>
                        <th scope="col" class="py-3.5 px-3">Score (Max 100)</th>
                        <th scope="col" class="py-3.5 px-3 text-center">Letter Grade</th>
                        <th scope="col" class="py-3.5 px-3">Status</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <!-- Table Body Rows (Modular Partial) -->
                    @include('admin.reports.table')
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @include('share.pagination', ['paginator' => $marks])
    </div>
</div>
@endsection
