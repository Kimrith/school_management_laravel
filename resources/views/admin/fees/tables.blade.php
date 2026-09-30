<table class="w-full text-left border-collapse">
    <thead>
        <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
            <th scope="col" class="py-3.5 pl-6 pr-3">Student</th>
            <th scope="col" class="py-3.5 px-3">Classroom</th>
            <th scope="col" class="py-3.5 px-3">Tuition Fee</th>
            <th scope="col" class="py-3.5 px-3">Paid & Balance</th>
            <th scope="col" class="py-3.5 px-3">Payment Status</th>
            <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Update Payment Status</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-100 text-sm">
        @forelse($invoices as $inv)
            @php
                $student = $inv->student;
                $user = $student?->user;
                $classroom = $student?->classroom;
                $amount = (float) $inv->amount;
                
                // Calculate Paid & Remaining balance
                if ($inv->status === 'paid') {
                    $paidAmount = $amount;
                    $balance = 0.00;
                } elseif ($inv->status === 'partial') {
                    $paidAmount = $amount * 0.50; // 50% paid
                    $balance = $amount * 0.50;
                } else {
                    $paidAmount = 0.00;
                    $balance = $amount;
                }
            @endphp
            <tr class="hover:bg-slate-50/60 transition-colors">
                <!-- Student Info -->
                <td class="py-4 pl-6 pr-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0 border border-indigo-100">
                            {{ substr($user?->name ?? 'S', 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-900 leading-snug truncate">{{ $user?->name ?? 'Unassigned Student' }}</p>
                            <p class="text-xs font-mono text-slate-400">{{ $student?->student_code ?? 'STU-' . $inv->student_id }}</p>
                        </div>
                    </div>
                </td>

                <!-- Classroom -->
                <td class="py-4 px-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200/60">
                        {{ $classroom?->name ?? 'No Class' }}
                    </span>
                </td>

                <!-- Fee Amount -->
                <td class="py-4 px-3 font-semibold text-slate-900 font-mono text-xs">
                    ${{ number_format($amount, 2) }}
                    <span class="block text-[11px] font-normal text-slate-400 font-sans">Term Tuition</span>
                </td>

                <!-- Paid vs Balance -->
                <td class="py-4 px-3 text-xs">
                    <div class="flex flex-col">
                        <span class="font-semibold {{ $inv->status === 'paid' ? 'text-emerald-700' : ($inv->status === 'partial' ? 'text-amber-700' : 'text-slate-500') }}">
                            Paid: ${{ number_format($paidAmount, 2) }}
                        </span>
                        <span class="text-[11px] {{ $balance > 0 ? 'text-rose-500 font-medium' : 'text-slate-400' }}">
                            Due: ${{ number_format($balance, 2) }}
                        </span>
                    </div>
                </td>

                <!-- Payment Status Badge -->
                <td class="py-4 px-3">
                    @if($inv->status === 'paid')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Fully Paid (100%)
                        </span>
                    @elseif($inv->status === 'partial')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Paid 50%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Unpaid (0%)
                        </span>
                    @endif
                </td>

                <!-- Update Payment Status Form Actions -->
                <td class="py-4 pl-3 pr-6 text-right">
                    <form action="{{ route('admin.fees.update-status', $inv->id) }}" method="POST" class="inline-flex items-center gap-1.5">
                        @csrf
                        @method('PATCH')

                        @if($inv->status === 'unpaid')
                            <!-- Action: Pay 50% -->
                            <button 
                                type="submit" 
                                name="status" 
                                value="partial" 
                                title="Record 50% payment ($175.00)"
                                class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 transition-colors cursor-pointer"
                            >
                                Pay 50%
                            </button>

                            <!-- Action: Pay Full 100% -->
                            <button 
                                type="submit" 
                                name="status" 
                                value="paid" 
                                title="Mark as fully paid ($350.00)"
                                class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition-colors cursor-pointer shadow-2xs"
                            >
                                Mark Paid
                            </button>
                        @elseif($inv->status === 'partial')
                            <!-- Action: Complete remaining 50% -->
                            <button 
                                type="submit" 
                                name="status" 
                                value="paid" 
                                title="Pay remaining 50% to complete payment"
                                class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition-colors cursor-pointer shadow-2xs"
                            >
                                Pay Remaining 50%
                            </button>

                            <!-- Action: Reset to unpaid -->
                            <button 
                                type="submit" 
                                name="status" 
                                value="unpaid" 
                                title="Revert to unpaid"
                                class="px-2 py-1 text-xs font-medium rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                            >
                                Revert
                            </button>
                        @else
                            <!-- Already Paid: Options to revert if needed -->
                            <span class="inline-flex items-center gap-1 text-xs text-emerald-700 font-medium mr-1">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Cleared
                            </span>

                            <button 
                                type="submit" 
                                name="status" 
                                value="partial" 
                                title="Change to 50% partial payment"
                                class="px-2 py-1 text-[11px] font-medium rounded-lg text-slate-500 hover:text-amber-700 hover:bg-amber-50 transition-colors cursor-pointer"
                            >
                                50%
                            </button>

                            <button 
                                type="submit" 
                                name="status" 
                                value="unpaid" 
                                title="Change to unpaid"
                                class="px-2 py-1 text-[11px] font-medium rounded-lg text-slate-500 hover:text-rose-700 hover:bg-rose-50 transition-colors cursor-pointer"
                            >
                                Reset
                            </button>
                        @endif
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="py-12 text-center text-slate-400">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">No students found matching your filters</p>
                        <p class="text-xs text-slate-400 mt-1">Try resetting the class or status filter.</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination Footer -->
@include('share.pagination', ['paginator' => $invoices])