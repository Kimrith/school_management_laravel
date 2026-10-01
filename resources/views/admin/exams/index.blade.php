@extends('layouts.admin')

@section('title', 'Examinations & Marks')
@section('page_title', 'Exams & Marks')

@section('content')
<div class="space-y-6" x-data="{ addExamModal: {{ $errors->any() ? 'true' : 'false' }} }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Examinations & Assessments</h1>
            <p class="text-sm text-slate-500 mt-1">Schedule tests, assign total marks, and oversee academic grade records.</p>
        </div>
        <div class="flex items-center gap-3">
            <button 
                @click="addExamModal = true"
                type="button" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm hover:shadow-md transition-all cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Schedule Exam</span>
            </button>
        </div>
    </div>

    <!-- Success Message Alert -->
    @if(session('success'))
        <div class="p-4 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-xl border border-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Messages Alert -->
    @if($errors->any())
        <div class="p-4 text-xs font-semibold text-rose-700 bg-rose-50 rounded-xl border border-rose-200">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Exams Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100">
            <form method="GET" action="{{ route('admin.exams.index') }}" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <!-- Search Input -->
                <div class="relative w-full sm:w-64">
                    <input 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search exam, subject..." 
                        class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                    >
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                </div>

                <!-- Classroom Select Filter -->
                <select 
                    name="classroom_id" 
                    onchange="this.form.submit()"
                    class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                >
                    <option value="">All Classrooms</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>

                <button 
                    type="submit" 
                    class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700 transition-colors cursor-pointer"
                >
                    Filter
                </button>

                @if(request()->filled('search') || request()->filled('classroom_id'))
                    <a 
                        href="{{ route('admin.exams.index') }}" 
                        class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline"
                    >
                        Clear
                    </a>
                @endif
            </form>

            <p class="text-xs text-slate-400 self-end sm:self-auto">
                Showing <span class="font-semibold text-slate-700">{{ $exams->total() }}</span> scheduled {{ Str::plural('exam', $exams->total()) }}
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <th scope="col" class="py-3.5 pl-6 pr-3">Exam Title</th>
                        <th scope="col" class="py-3.5 px-3">Subject</th>
                        <th scope="col" class="py-3.5 px-3">Classroom</th>
                        <th scope="col" class="py-3.5 px-3">Exam Date</th>
                        <th scope="col" class="py-3.5 px-3">Total Marks</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Actions</th>
                    </tr>
                </thead>

                @include('admin.exams.table')

            </table>
        </div>

        <!-- Pagination Footer -->
        @include('share.pagination', ['paginator' => $exams])
    </div>

    <!-- Modal Component -->
    @include('admin.exams.insert')
</div>
@endsection