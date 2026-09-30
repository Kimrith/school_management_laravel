    <!-- Financial KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Expected -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Expected</span>
            <p class="text-2xl font-bold text-slate-900 mt-2">${{ number_format((float)($totalBilled ?? 0), 2) }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $totalStudents ?? 0 }} enrolled students ($350/student)</p>
        </div>

        <!-- Card 2: Total Collected -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Total Collected</span>
            <p class="text-2xl font-bold text-emerald-700 mt-2">${{ number_format((float)($totalCollected ?? 0), 2) }}</p>
            <span class="inline-flex items-center text-xs font-semibold text-emerald-600 mt-1">
                {{ number_format((float)($collectionRate ?? 0), 1) }}% collection rate ({{ $paidCount ?? 0 }} fully paid)
            </span>
        </div>

        <!-- Card 3: 50% Partial Paid -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Paid 50% (Partial)</span>
            <p class="text-2xl font-bold text-amber-700 mt-2">{{ $partialCount ?? 0 }} Students</p>
            <p class="text-xs text-slate-400 mt-1">$175.00 paid per student (half term)</p>
        </div>

        <!-- Card 4: Unpaid Balance -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Outstanding Balance</span>
            <p class="text-2xl font-bold text-rose-700 mt-2">${{ number_format((float)($pendingAmount ?? 0), 2) }}</p>
            <p class="text-xs text-rose-500 mt-1">{{ $unpaidCount ?? 0 }} students have not paid yet</p>
        </div>
    </div>