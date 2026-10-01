    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <span class="text-xs font-semibold text-emerald-700">Present</span>
            <span class="text-lg font-bold text-emerald-700 font-mono" x-text="count('present')">0</span>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <span class="text-xs font-semibold text-rose-700">Absent</span>
            <span class="text-lg font-bold text-rose-700 font-mono" x-text="count('absent')">0</span>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <span class="text-xs font-semibold text-amber-700">Late</span>
            <span class="text-lg font-bold text-amber-700 font-mono" x-text="count('late')">0</span>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <span class="text-xs font-semibold text-sky-700">Excused</span>
            <span class="text-lg font-bold text-sky-700 font-mono" x-text="count('excused')">0</span>
        </div>
    </div>