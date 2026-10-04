<!-- Recent Student Registrations Table (2 Cols on Desktop) -->
<div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
    <!-- Table Header Toolbar -->
    <div class="p-5 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100">
        <div>
            <h2 class="text-base font-bold text-slate-900">Recent Student Admissions</h2>
            <p class="text-xs text-slate-400 mt-0.5">Latest students enrolled into the system</p>
        </div>

        <div class="flex items-center gap-2.5">
            <!-- View All Link -->
            <a 
                href="{{ route('admin.students.index') }}" 
                class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 shrink-0 px-3 py-1.5 hover:bg-indigo-50 rounded-xl transition-colors inline-flex items-center gap-1"
            >
                <span>View All Students</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Table Responsive Container -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                    <th scope="col" class="py-3.5 pl-6 pr-3">Student</th>
                    <th scope="col" class="py-3.5 px-3">Classroom</th>
                    <th scope="col" class="py-3.5 px-3">Gender</th>
                    <th scope="col" class="py-3.5 px-3">Parent / Contact</th>
                    <th scope="col" class="py-3.5 px-3">Status</th>
                    <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($recentStudents as $student)
                    @php
                        $avatarColors = ['bg-indigo-500', 'bg-emerald-500', 'bg-purple-500', 'bg-sky-500', 'bg-rose-500'];
                        $bgColor = $avatarColors[$loop->index % count($avatarColors)];
                        $name = $student->user->name ?? 'Student';
                        $initials = strtoupper(substr($name, 0, 2));
                        $userStatus = $student->user?->status;
                        $status = strtolower($userStatus instanceof \App\Enums\StudentStatus ? $userStatus->value : (string) ($userStatus ?? 'active'));
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <!-- Student Name & Code -->
                        <td class="py-3.5 pl-6 pr-3">
                            <div class="flex items-center gap-3">
                                @if($student->avatar_url)
                                    <img src="{{ $student->avatar_url }}" alt="{{ $name }}" class="w-9 h-9 rounded-xl object-cover shadow-2xs shrink-0 border border-slate-200/80">
                                @else
                                    <div class="w-9 h-9 rounded-xl {{ $bgColor }} text-white text-xs font-bold flex items-center justify-center shadow-2xs shrink-0">
                                        {{ $initials }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-900 leading-snug">{{ $name }}</p>
                                    <p class="text-xs font-mono text-slate-400">{{ $student->student_code }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Classroom -->
                        <td class="py-3.5 px-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium">
                                {{ $student->classroom->name ?? 'Not Assigned' }}
                            </span>
                        </td>

                        <!-- Gender -->
                        <td class="py-3.5 px-3 text-xs capitalize text-slate-600">
                            {{ $student->gender ?? 'N/A' }}
                        </td>

                        <!-- Parent Info -->
                        <td class="py-3.5 px-3">
                            <p class="text-xs font-medium text-slate-800">{{ $student->parent_name ?: 'N/A' }}</p>
                            <p class="text-[11px] text-slate-400">{{ $student->parent_phone ?: 'No Phone' }}</p>
                        </td>

                        <!-- Status Badge -->
                        <td class="py-3.5 px-3">
                            @if($status === 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Enrolled
                                </span>
                            @elseif($status === 'suspended' || $status === 'inactive')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Suspended
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Pending
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 pl-3 pr-6 text-right">
                            <div class="inline-flex items-center gap-1">
                                <a 
                                    href="{{ route('admin.students.edit', $student) }}" 
                                    title="Edit Student Record" 
                                    class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-xs text-slate-400">
                            No recent student admissions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Table Pagination / Summary Footer -->
    <div class="p-4 px-6 bg-slate-50/50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
        <p>Showing <span class="font-semibold text-slate-800">{{ $recentStudents->count() }}</span> of <span class="font-semibold text-slate-800">{{ $totalStudents }}</span> total students</p>
        <a 
            href="{{ route('admin.students.index') }}" 
            class="inline-flex items-center gap-1.5 font-semibold text-indigo-600 hover:text-indigo-800 transition-colors"
        >
            <span>Go to Student Registry</span>
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </a>
    </div>
</div>