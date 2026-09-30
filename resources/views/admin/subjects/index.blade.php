@extends('layouts.admin')

@section('title', 'Curriculum & Subjects')
@section('page_title', 'Subjects')

@section('content')
<div 
    class="space-y-6" 
    x-data="{ 
        addSubjectModal: {{ $errors->any() ? 'true' : 'false' }}, 
        editSubjectModal: false, 
        deleteSubjectModal: false, 
        searchQuery: '',
        selectedSubject: { 
            id: '',
            name: '', 
            code: '', 
            level_id: '',
            level_name: '',
            classroom_ids: [],
            classroom_id: '',
            classroom_name: '',
            desc: ''
        } 
    }"
>
    <!-- Header with Breadcrumbs & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Academic Subjects</h1>
            <p class="text-sm text-slate-500 mt-1">Manage school curriculum, course syllabi, classrooms, and subject allocations.</p>
        </div>
        <div class="flex items-center gap-3">
            <button 
                @click="addSubjectModal = true"
                type="button" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm hover:shadow-md transition-all duration-150 cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Subject</span>
            </button>
        </div>
    </div>


    <!-- Quick Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Subjects</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ count($subjectsList) }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                📚
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Available Classrooms</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ count($classrooms) }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                🏫
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Curriculum Status</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">Active</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                ✓
            </div>
        </div>
    </div>

    <!-- Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:w-80">
            <input 
                type="text" 
                x-model="searchQuery"
                placeholder="Search subject by title or code..." 
                class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
            >
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
        </div>

        <div class="flex items-center gap-2 text-xs text-slate-500 self-end sm:self-auto">
            <span>Showing active curriculum subjects</span>
        </div>
    </div>

    <!-- Subjects Grid (Table Component) -->
    @include('admin.subjects.table')

    <!-- Modals (Add, Edit, Delete) -->
    @include('admin.subjects.insert')
    @include('admin.subjects.edit')
    @include('admin.subjects.delete')
</div>
@endsection
