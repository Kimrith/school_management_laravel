<!-- Analytics KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Average Score -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Average Score</p>
            <div class="flex items-baseline gap-1.5 mt-1">
                <span class="text-2xl font-extrabold text-slate-900 font-mono">{{ $averageScore }}</span>
                <span class="text-xs font-semibold text-slate-400">/ 100</span>
            </div>
            <span class="inline-flex items-center text-[11px] font-semibold text-indigo-600 mt-0.5">Overall Performance</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg shadow-2xs">
            📊
        </div>
    </div>

    <!-- Pass Rate -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pass Rate (&ge; 50)</p>
            <div class="flex items-baseline gap-1.5 mt-1">
                <span class="text-2xl font-extrabold text-emerald-600 font-mono">{{ $passRate }}%</span>
            </div>
            <span class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $passedCount }} of {{ $totalMarksCount }} passed</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg shadow-2xs">
            🎓
        </div>
    </div>

    <!-- Highest Score -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Highest Score</p>
            <div class="flex items-baseline gap-1.5 mt-1">
                <span class="text-2xl font-extrabold text-amber-600 font-mono">{{ number_format($highestScore, 2) }}</span>
                <span class="text-xs font-semibold text-slate-400">Marks</span>
            </div>
            <span class="text-[11px] font-medium text-slate-400 mt-0.5">Top examination mark</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg shadow-2xs">
            🏆
        </div>
    </div>

    <!-- Total Submissions -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Graded Marks</p>
            <div class="flex items-baseline gap-1.5 mt-1">
                <span class="text-2xl font-extrabold text-slate-900 font-mono">{{ $totalMarksCount }}</span>
            </div>
            <span class="text-[11px] font-medium text-slate-400 mt-0.5">Recorded in database</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-lg shadow-2xs">
            📝
        </div>
    </div>
</div>
