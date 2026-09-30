           <!-- Fee Collection Summary Card -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Term Fee Collection</h3>
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">85.6%</span>
                </div>
                
                <div class="mt-4">
                    <div class="flex items-baseline justify-between text-xs text-slate-500">
                        <span>Collected</span>
                        <span class="font-bold text-slate-900 text-base">$42,800 <span class="text-xs font-normal text-slate-400">/ $50,000</span></span>
                    </div>
                    <!-- Progress Bar -->
                    <div class="w-full h-2.5 rounded-full bg-slate-100 mt-2 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-emerald-500" style="width: 85.6%"></div>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3 text-center">
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <p class="text-xs text-slate-400">Paid Invoices</p>
                        <p class="text-sm font-bold text-slate-800 mt-0.5">342</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-rose-50/50 border border-rose-100">
                        <p class="text-xs text-rose-500">Unpaid / Due</p>
                        <p class="text-sm font-bold text-rose-700 mt-0.5">58</p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Examinations -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Upcoming Exams</h3>
                    <a href="{{ url('/admin/exams') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View All</a>
                </div>

                <div class="mt-4 space-y-3">
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-sky-100 text-sky-700 font-bold text-xs flex flex-col items-center justify-center shrink-0">
                            <span>15</span>
                            <span class="text-[9px] uppercase">Oct</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-800 truncate">Web Development Midterm</p>
                            <p class="text-[11px] text-slate-400">Grade 10-A &bull; 100 Marks</p>
                        </div>
                        <span class="text-[11px] font-medium text-sky-700 bg-sky-50 px-2 py-0.5 rounded-md">Exam</span>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-700 font-bold text-xs flex flex-col items-center justify-center shrink-0">
                            <span>22</span>
                            <span class="text-[9px] uppercase">Oct</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-800 truncate">Mathematics Final</p>
                            <p class="text-[11px] text-slate-400">Grade 12-A &bull; 100 Marks</p>
                        </div>
                        <span class="text-[11px] font-medium text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md">Exam</span>
                    </div>
                </div>
            </div>

            <!-- Quick Management Shortcuts -->
            <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-2xl p-5 text-white shadow-md">
                <h4 class="text-sm font-bold">Quick Academic Actions</h4>
                <p class="text-xs text-indigo-200 mt-1">Direct operations for administrators</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ url('/admin/attendances') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-medium text-white transition-colors">
                        <span>Record Today's Attendance</span>
                        <svg class="w-4 h-4 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                    <a href="{{ url('/admin/fees') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-medium text-white transition-colors">
                        <span>Generate Fee Invoices</span>
                        <svg class="w-4 h-4 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            </div>