@extends('layouts.admin')

@section('title', 'Fee & Invoices Management')
@section('page_title', 'Fee Invoices')

@section('content')
<div class="space-y-6" x-data="{ invoiceModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Fee Billing & Payments</h1>
            <p class="text-sm text-slate-500 mt-1">Track student invoices, tuition collection, and outstanding dues.</p>
        </div>
        <div class="flex items-center gap-3">
            <button 
                @click="invoiceModal = true"
                type="button" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm hover:shadow-md transition-all duration-150 cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Create Invoice</span>
            </button>
        </div>
    </div>

    <!-- Financial KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Billed</span>
            <p class="text-2xl font-bold text-slate-900 mt-2">$64,250.00</p>
            <p class="text-xs text-slate-400 mt-1">Academic Year 2025-2026</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Total Collected</span>
            <p class="text-2xl font-bold text-emerald-700 mt-2">$54,990.00</p>
            <span class="inline-flex items-center text-xs font-semibold text-emerald-600 mt-1">85.6% collection rate</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Pending / Partial</span>
            <p class="text-2xl font-bold text-amber-700 mt-2">$6,450.00</p>
            <p class="text-xs text-slate-400 mt-1">24 invoices awaiting clearance</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Overdue Balance</span>
            <p class="text-2xl font-bold text-rose-700 mt-2">$2,810.00</p>
            <p class="text-xs text-rose-400 mt-1">11 overdue student invoices</p>
        </div>
    </div>

    <!-- Fee Invoices Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <input 
                    type="text" 
                    placeholder="Search invoices, student..." 
                    class="w-full sm:w-64 pl-3.5 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                >
                <select class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700">
                    <option value="">All Statuses</option>
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="partial">Partial</option>
                </select>
            </div>
            <p class="text-xs text-slate-400">Sorted by newest due date</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <th scope="col" class="py-3.5 pl-6 pr-3">Invoice #</th>
                        <th scope="col" class="py-3.5 px-3">Student</th>
                        <th scope="col" class="py-3.5 px-3">Fee Title</th>
                        <th scope="col" class="py-3.5 px-3">Amount</th>
                        <th scope="col" class="py-3.5 px-3">Due Date</th>
                        <th scope="col" class="py-3.5 px-3">Status</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @php
                        $invoices = [
                            ['no' => 'INV-2026-001', 'student' => 'Sokha Chan', 'code' => 'STU-1001', 'class' => 'Grade 10-A', 'title' => 'Term 1 Tuition Fee', 'amount' => '350.00', 'due' => 'Oct 15, 2026', 'paid_date' => 'Oct 02, 2026', 'status' => 'paid'],
                            ['no' => 'INV-2026-002', 'student' => 'Bopha Vong', 'code' => 'STU-1002', 'class' => 'Grade 10-A', 'title' => 'Term 1 Tuition Fee', 'amount' => '350.00', 'due' => 'Oct 15, 2026', 'paid_date' => 'Oct 05, 2026', 'status' => 'paid'],
                            ['no' => 'INV-2026-003', 'student' => 'Dara Rath', 'code' => 'STU-1003', 'class' => 'Grade 11-B', 'title' => 'Laboratory Equipment Fee', 'amount' => '75.00', 'due' => 'Oct 20, 2026', 'paid_date' => null, 'status' => 'unpaid'],
                            ['no' => 'INV-2026-004', 'student' => 'Chanthou Seng', 'code' => 'STU-1004', 'class' => 'Grade 12-A', 'title' => 'Annual Examination Fee', 'amount' => '120.00', 'due' => 'Oct 28, 2026', 'paid_date' => null, 'status' => 'partial'],
                            ['no' => 'INV-2026-005', 'student' => 'Panha Lim', 'code' => 'STU-1005', 'class' => 'Grade 9-C', 'title' => 'Term 1 Tuition Fee', 'amount' => '350.00', 'due' => 'Sep 30, 2026', 'paid_date' => null, 'status' => 'unpaid'],
                        ];
                    @endphp

                    @foreach($invoices as $inv)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 pl-6 pr-3 font-mono font-medium text-xs text-slate-700">
                                {{ $inv['no'] }}
                            </td>
                            <td class="py-4 px-3">
                                <p class="font-semibold text-slate-900 leading-snug">{{ $inv['student'] }}</p>
                                <p class="text-xs text-slate-400 font-mono">{{ $inv['code'] }} &bull; {{ $inv['class'] }}</p>
                            </td>
                            <td class="py-4 px-3 text-xs font-medium text-slate-700">
                                {{ $inv['title'] }}
                            </td>
                            <td class="py-4 px-3 font-semibold text-slate-900 font-mono">
                                ${{ number_format((float)$inv['amount'], 2) }}
                            </td>
                            <td class="py-4 px-3 text-xs text-slate-500">
                                {{ $inv['due'] }}
                            </td>
                            <td class="py-4 px-3">
                                @if($inv['status'] === 'paid')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Paid
                                    </span>
                                @elseif($inv['status'] === 'partial')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Partial
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Unpaid
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 pl-3 pr-6 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button" title="Record Payment" class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6H2.25m0 0v8.25m0 0a60.07 60.07 0 0 0 15.797 2.101c.727.198 1.453-.342 1.453-1.096V14.25m-17.25 0h17.25m-17.25 0a3.75 3.75 0 0 1-3.75-3.75V6.75A3.75 3.75 0 0 1 3.75 3h16.5A3.75 3.75 0 0 1 24 6.75v3.75a3.75 3.75 0 0 1-3.75 3.75h-.375" />
                                        </svg>
                                    </button>
                                    <button type="button" title="Print Receipt" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Invoice Modal -->
    <div x-show="invoiceModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            <div x-show="invoiceModal" @click="invoiceModal = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs"></div>
            <div x-show="invoiceModal" class="relative bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-xl border border-slate-200/80 my-8 z-10">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Issue Fee Invoice</h3>
                    <button @click="invoiceModal = false" class="p-1 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="#" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Student</label>
                        <select class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                            <option>Sokha Chan (STU-1001) - Grade 10-A</option>
                            <option>Bopha Vong (STU-1002) - Grade 10-A</option>
                            <option>Dara Rath (STU-1003) - Grade 11-B</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Fee Description</label>
                        <input type="text" placeholder="e.g. Term 2 Tuition Fee" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Amount ($ USD)</label>
                            <input type="number" step="0.01" placeholder="350.00" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Due Date</label>
                            <input type="date" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="invoiceModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 rounded-xl border border-slate-200">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm">Issue Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
