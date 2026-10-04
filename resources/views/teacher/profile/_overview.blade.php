<!-- TAB 1: Faculty Overview & Bio -->
<div x-show="activeTab === 'overview'" x-cloak class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Columns -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Academic Credentials & Legal Identity -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Faculty Credentials &amp; Registry</h2>
                            <p class="text-xs text-slate-400">Official university appointment and civil records</p>
                        </div>
                    </div>

                    <button 
                        @click="editModalOpen = true"
                        type="button"
                        class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 hover:underline inline-flex items-center gap-1 cursor-pointer"
                    >
                        <span>Edit</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>

                <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-y-5 gap-x-6 text-sm">
                    <div>
                        <span class="text-xs font-medium text-slate-400 block mb-1">Full Legal Name</span>
                        <span class="font-bold text-slate-900 text-base">{{ $facultyName }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-medium text-slate-400 block mb-1">Faculty Staff Code</span>
                        <span class="font-mono font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200 text-xs inline-block">
                            {{ $staffId }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs font-medium text-slate-400 block mb-1">Highest Qualification</span>
                        <span class="font-semibold text-slate-800 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                            </svg>
                            {{ $qualification ?: 'Not specified' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs font-medium text-slate-400 block mb-1">Specialization</span>
                        <span class="font-semibold text-slate-800">{{ $specialization ?: 'Not specified' }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-medium text-slate-400 block mb-1">Academic Role</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Faculty Member
                        </span>
                    </div>

                    <div>
                        <span class="text-xs font-medium text-slate-400 block mb-1">Faculty Status</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Active Faculty
                        </span>
                    </div>

                    <div>
                        <span class="text-xs font-medium text-slate-400 block mb-1">Assigned Classes Count</span>
                        <span class="font-medium text-slate-800">{{ $classCount }} {{ \Illuminate\Support\Str::plural('Classroom', $classCount) }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-medium text-slate-400 block mb-1">Teaching Subjects Count</span>
                        <span class="font-medium text-slate-800">{{ $subjectCount }} {{ \Illuminate\Support\Str::plural('Course', $subjectCount) }}</span>
                    </div>
                </div>
            </div>

            <!-- Contact & Campus Office Details -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Communication &amp; Contact Details</h2>
                            <p class="text-xs text-slate-400">Institutional email, direct phone, and address</p>
                        </div>
                    </div>
                </div>

                <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-y-5 gap-x-6 text-sm">
                    <div>
                        <span class="text-xs font-medium text-slate-400 block mb-1">Institutional Email</span>
                        @if($facultyEmail)
                            <a href="mailto:{{ $facultyEmail }}" class="font-semibold text-emerald-700 hover:underline flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                <span>{{ $facultyEmail }}</span>
                            </a>
                        @else
                            <span class="text-slate-400 font-medium">Not provided</span>
                        @endif
                    </div>

                    <div>
                        <span class="text-xs font-medium text-slate-400 block mb-1">Direct Phone</span>
                        @if($facultyPhone)
                            <a href="tel:{{ $facultyPhone }}" class="font-bold text-slate-800 hover:text-emerald-700 font-mono flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                </svg>
                                <span>{{ $facultyPhone }}</span>
                            </a>
                        @else
                            <span class="text-slate-400 font-medium">Not provided</span>
                        @endif
                    </div>

                    <div>
                        <span class="text-xs font-medium text-slate-400 block mb-1">Primary Classroom Location</span>
                        <p class="font-semibold text-slate-800">{{ $assignedClassrooms->first()?->room ? 'Room ' . $assignedClassrooms->first()->room : ($assignedClassrooms->first()?->name ?? 'Campus Main Building') }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-medium text-slate-400 block mb-1">Academic Department</span>
                        <p class="font-semibold text-slate-800">Dept. of Computer Science &amp; Engineering</p>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="text-xs font-medium text-slate-400 block mb-1">Residence / Mailing Address</span>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-start gap-3">
                            <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 0 1 15 0Z" />
                            </svg>
                            <div>
                                <p class="font-medium text-slate-800">{{ $address ?: 'No registered address on file' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right 1 Column: Department & Faculty Info Card -->
        <div class="space-y-6">
            <!-- Department Hierarchy Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Academic Division</h3>
                            <p class="text-xs text-slate-400">Department affiliation</p>
                        </div>
                    </div>
                </div>

                <div class="p-5 space-y-4 text-sm">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-3">
                        <div>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block">Faculty Division</span>
                            <p class="text-sm font-bold text-slate-900 mt-0.5">Faculty of Science &amp; Technology</p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-xs font-semibold">Academic Staff</span>
                        </div>

                        <hr class="border-slate-200/80">

                        <div>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block">Assigned Sections</span>
                            <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $assignedClassrooms->pluck('name')->join(', ') ?: 'No classrooms currently assigned' }}</p>
                        </div>

                        <div>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block">Instructional Focus</span>
                            <p class="text-xs font-medium text-slate-700 mt-0.5">{{ $specialization ?: 'Curriculum Teaching & Student Guidance' }}</p>
                        </div>
                    </div>

                    <!-- Campus Emergency Notice -->
                    <div class="p-3.5 rounded-xl bg-emerald-50/80 border border-emerald-200/80 flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                        <div class="text-xs text-emerald-900 leading-relaxed">
                            <p class="font-semibold">Campus Gate &amp; Facility Access</p>
                            <p class="mt-0.5 text-emerald-800">Your faculty pass grants active access to designated academic facilities and instructional rooms.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fast Shortcuts -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 space-y-3">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Teacher Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ url('/teacher/attendance') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-100 text-xs font-semibold text-slate-700 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span>Mark Classroom Attendance</span>
                        </span>
                        <span class="text-slate-400">&rarr;</span>
                    </a>

                    <a href="{{ url('/teacher/grades') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-100 text-xs font-semibold text-slate-700 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <span>Input Examination Marks</span>
                        </span>
                        <span class="text-slate-400">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
