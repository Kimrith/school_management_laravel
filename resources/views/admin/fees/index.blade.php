@extends('layouts.admin')

@section('title', 'Fee & Invoices Management')
@section('page_title', 'Fee Invoices')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Fee Billing & Payments</h1>
            <p class="text-sm text-slate-500 mt-1">Track student fee payment statuses: Fully Paid, 50% Partial, and Unpaid.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Standard Tuition: $350.00 / Term
            </span>
        </div>
    </div>

    @include('admin.fees.stats-card')

    <!-- Fee Invoices Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100">
            <form method="GET" action="{{ route('admin.fees.index') }}" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <!-- Search Input -->
                <div class="relative w-full sm:w-64">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search student name, code..." 
                        class="w-full pl-8.5 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                </div>

                <!-- Status Filter -->
                <select 
                    name="status" 
                    onchange="this.form.submit()" 
                    class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 cursor-pointer"
                >
                    <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Statuses</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid (100%)</option>
                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Paid 50% (Partial)</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid (0%)</option>
                </select>

                <!-- Class Filter -->
                <select 
                    name="classroom_id" 
                    onchange="this.form.submit()" 
                    class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 cursor-pointer"
                >
                    <option value="all">All Classes</option>
                    @foreach($classrooms as $cls)
                        <option value="{{ $cls->id }}" {{ request('classroom_id') == $cls->id ? 'selected' : '' }}>
                            {{ $cls->name }}
                        </option>
                    @endforeach
                </select>

                @if(request('search') || (request('status') && request('status') !== 'all') || (request('classroom_id') && request('classroom_id') !== 'all'))
                    <a href="{{ route('admin.fees.index') }}" class="text-xs text-rose-600 hover:text-rose-800 font-semibold underline">
                        Reset Filters
                    </a>
                @endif
            </form>

            <p class="text-xs text-slate-400 shrink-0">Showing {{ $invoices->total() }} student fee records</p>
        </div>

        <div class="overflow-x-auto">
            @include('admin.fees.tables')
        </div>
    </div>
</div>
@endsection
