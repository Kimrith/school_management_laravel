            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <th scope="col" class="py-3.5 pl-6 pr-3">Student Name</th>
                        <th scope="col" class="py-3.5 px-3">Student Code</th>
                        <th scope="col" class="py-3.5 px-3">Classroom</th>
                        <th scope="col" class="py-3.5 px-3">Date</th>
                        <th scope="col" class="py-3.5 px-3">Status</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($attendances as $row)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 pl-6 pr-3 font-semibold text-slate-900">
                                {{ $row->student?->user?->name ?? 'Student #' . $row->student_id }}
                            </td>
                            <td class="py-3.5 px-3 font-mono text-xs text-indigo-600 font-semibold">
                                {{ $row->student?->student_code ?? 'N/A' }}
                            </td>
                            <td class="py-3.5 px-3 text-xs text-slate-700">
                                {{ $row->classroom?->name ?? 'Unassigned' }}
                            </td>
                            <td class="py-3.5 px-3 text-xs text-slate-500 font-mono">
                                {{ $row->date->format('M d, Y') }}
                            </td>
                            <td class="py-3.5 px-3">
                                @if($row->status === 'present')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Present
                                    </span>
                                @elseif($row->status === 'absent')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Absent
                                    </span>
                                @elseif($row->status === 'late')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Late
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-50 text-sky-700 border border-sky-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                        Excused
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 pl-3 pr-6 text-right text-xs text-slate-500">
                                {{ $row->remarks ?: '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                        </svg>
                                    </div>
                                    <p class="font-medium text-slate-700 text-sm">No attendance records found</p>
                                    <p class="text-xs text-slate-400 mt-1">There are no attendance records matching the selected date or filters.</p>
                                    <a 
                                        href="{{ route('teacher.attendance.index') }}" 
                                        class="mt-3 px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-semibold transition-colors"
                                    >
                                        Record Attendance Now
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>