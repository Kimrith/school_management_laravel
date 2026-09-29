<tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($students as $student)
                        @php
                            $studentStatus = $student->user->status?->value ?? $student->user->status ?? 'active';
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 pl-6 pr-3 font-mono font-bold text-xs text-indigo-600">
                                {{ $student->student_code }}
                            </td>
                            <td class="py-4 px-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-100 to-sky-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0 border border-indigo-200/60">
                                        {{ strtoupper(substr($student->user->name ?? 'ST', 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 leading-snug">{{ $student->user->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-slate-400">{{ $student->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">
                                    {{ $student->classroom->name ?? 'Unassigned' }}
                                </span>
                            </td>
                            
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">
                                    {{ $student->classroom->level ?? 'Unassigned' }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-xs capitalize font-medium text-slate-600">
                                {{ $student->gender }}
                            </td>
                            <td class="py-4 px-3">
                                <p class="text-xs font-semibold text-slate-800">{{ $student->parent_name ?? 'N/A' }}</p>
                                <p class="text-[11px] text-slate-400 font-mono">{{ $student->parent_phone ?? 'N/A' }}</p>
                            </td>
                            <td class="py-4 px-3">
                                @if($studentStatus === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                @elseif($studentStatus === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/70">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Pending
                                    </span>
                                @elseif($studentStatus === 'inactive')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Inactive
                                    </span>
                                @elseif($studentStatus === 'suspended')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        {{ ucfirst($studentStatus) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 pl-3 pr-6 text-right">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    <!-- View Profile -->
                                    <a 
                                        href="{{ url('/student/profile') }}" 
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" 
                                        title="View Student Profile"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>

                                    <!-- Edit Student -->
                                    <a 
                                        href="{{ route('admin.students.edit', $student->id) }}" 
                                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all" 
                                        title="Edit Student"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </a>

                                    <!-- Suspend / Reinstate Toggle Action -->
                                    <form 
                                        method="POST" 
                                        action="{{ route('admin.students.toggle-status', $student->id) }}" 
                                        onsubmit="return confirm('{{ $studentStatus === 'suspended' ? 'Reinstate and activate student ' . $student->student_code . '?' : 'Suspend student ' . $student->student_code . ' (' . ($student->user->name ?? '') . ')?' }}')"
                                        class="inline-block"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        @if($studentStatus === 'suspended')
                                            <button 
                                                type="submit" 
                                                class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all cursor-pointer" 
                                                title="Reinstate Student"
                                            >
                                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                            </button>
                                        @else
                                            <button 
                                                type="submit" 
                                                class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all cursor-pointer" 
                                                title="Suspend Student"
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
                            <td colspan="7" class="py-14 text-center">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-3 shadow-xs">
                                        🎓
                                    </div>
                                    <h3 class="text-base font-bold text-slate-800">No students found</h3>
                                    <p class="text-xs text-slate-400 mt-1">No student records found matching the {{ $statusFilter !== 'all' ? $statusFilter : '' }} status or active filters.</p>
                                    <div class="flex items-center gap-2 mt-4">
                                        @if(request('status') && request('status') !== 'all')
                                            <a 
                                                href="{{ route('admin.students.index') }}" 
                                                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors"
                                            >
                                                View All Students
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
                                            <span>Add Student</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
</tbody>