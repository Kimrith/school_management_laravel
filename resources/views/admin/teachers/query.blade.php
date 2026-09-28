<tbody class="divide-y divide-slate-100 text-sm">
    @forelse($teachers as $teacher)
        @php
            $teacherStatus = $teacher->user->status?->value ?? $teacher->user->status ?? 'active';
        @endphp
        <tr class="hover:bg-slate-50/60 transition-colors">
            <!-- Instructor Info -->
            <td class="py-4 pl-6 pr-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-100 to-sky-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0 border border-indigo-200/60 shadow-2xs">
                        {{ strtoupper(substr($teacher->user->name ?? 'TC', 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-900 leading-snug">{{ $teacher->user->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-slate-400">{{ $teacher->user->email ?? '' }}</p>
                    </div>
                </div>
            </td>

            <!-- Qualification -->
            <td class="py-4 px-3">
                @if($teacher->qualification)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">
                        {{ $teacher->qualification }}
                    </span>
                @else
                    <span class="text-xs text-slate-400 italic">Not specified</span>
                @endif
            </td>

            <!-- Specialization -->
            <td class="py-4 px-3 text-xs font-medium text-slate-700">
                {{ $teacher->specialization ?? 'General Academics' }}
            </td>

            <!-- Contact -->
            <td class="py-4 px-3">
                <p class="text-xs font-semibold text-slate-800 font-mono">{{ $teacher->phone ?? 'N/A' }}</p>
                <p class="text-[11px] text-slate-400 truncate max-w-xs">{{ $teacher->address ?? 'No address' }}</p>
            </td>

            <!-- Status -->
            <td class="py-4 px-3">
                @if($teacherStatus === 'active')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Active
                    </span>
                @elseif($teacherStatus === 'inactive')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                        Inactive
                    </span>
                @elseif($teacherStatus === 'suspended')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        Suspended
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                        {{ ucfirst($teacherStatus) }}
                    </span>
                @endif
            </td>

            <!-- Actions -->
            <td class="py-4 pl-3 pr-6 text-right">
                <div class="inline-flex items-center gap-1.5 justify-end">
                    <!-- Edit Teacher -->
                    <a 
                        href="{{ route('admin.teachers.edit', $teacher->id) }}" 
                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all" 
                        title="Edit Teacher"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </a>

                    <!-- Suspend / Reinstate Toggle Action -->
                    <form 
                        method="POST" 
                        action="{{ route('admin.teachers.toggle-status', $teacher->id) }}" 
                        onsubmit="return confirm('{{ $teacherStatus === 'suspended' ? 'Reinstate and activate faculty member ' . ($teacher->user->name ?? '') . '?' : 'Suspend faculty member ' . ($teacher->user->name ?? '') . '?' }}')"
                        class="inline-block"
                    >
                        @csrf
                        @method('PATCH')
                        @if($teacherStatus === 'suspended')
                            <button 
                                type="submit" 
                                class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all cursor-pointer" 
                                title="Reinstate Faculty"
                            >
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </button>
                        @else
                            <button 
                                type="submit" 
                                class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all cursor-pointer" 
                                title="Suspend Faculty"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </button>
                        @endif
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="py-14 text-center">
                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-3 shadow-xs">
                        👨‍🏫
                    </div>
                    <h3 class="text-base font-bold text-slate-800">No faculty members found</h3>
                    <p class="text-xs text-slate-400 mt-1">No faculty records found matching the {{ $statusFilter !== 'all' ? $statusFilter : '' }} status or active filters.</p>
                    <div class="flex items-center gap-2 mt-4">
                        @if(request('status') && request('status') !== 'all')
                            <a 
                                href="{{ route('admin.teachers.index') }}" 
                                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors"
                            >
                                View All Faculty
                            </a>
                        @endif
                        <button 
                            type="button" 
                            @click="addModalOpen = true" 
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition-colors shadow-xs cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Add Teacher</span>
                        </button>
                    </div>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
