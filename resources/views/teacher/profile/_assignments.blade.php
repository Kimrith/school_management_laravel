<!-- TAB 2: Assigned Classes & Schedule -->
<div x-show="activeTab === 'assignments'" x-cloak class="space-y-6">
    <!-- Assigned Subjects Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Assigned Teaching Curriculum</h2>
                    <p class="text-xs text-slate-400">Courses and subjects assigned to your faculty profile</p>
                </div>
            </div>

            <a href="{{ url('/teacher/attendance') }}" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-colors self-start sm:self-auto">
                Take Attendance
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <th class="py-3.5 pl-6 pr-3">Subject / Course</th>
                        <th class="py-3.5 px-3">Subject Code</th>
                        <th class="py-3.5 px-3">Assigned Class</th>
                        <th class="py-3.5 px-3">Room / Location</th>
                        <th class="py-3.5 px-3">Enrolled</th>
                        <th class="py-3.5 pl-3 pr-6 text-right">Quick Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @php
                        $subjectAssignments = isset($teacherSubjects) && $teacherSubjects->isNotEmpty() 
                            ? $teacherSubjects 
                            : ($assignedSubjects->isNotEmpty() ? $assignedSubjects : collect());
                    @endphp

                    @forelse($subjectAssignments as $item)
                        @php
                            $subjectModel = $item instanceof \App\Models\TeacherSubject ? $item->subject : $item;
                            $classroomModel = $item instanceof \App\Models\TeacherSubject ? $item->classroom : $assignedClassrooms->first();
                            $code = $subjectModel?->code ?? ('SUB-' . str_pad($subjectModel?->id ?? 1, 3, '0', STR_PAD_LEFT));
                            $name = $subjectModel?->name ?? 'Course';
                            $desc = $subjectModel?->description ?? 'Academic Subject';
                            $className = $classroomModel?->name ?? 'Unassigned';
                            $room = $classroomModel?->room ? 'Room ' . $classroomModel->room : 'Campus Classroom';
                            $studentsInClass = $classroomModel?->studentProfiles?->count() ?? 0;
                            $actionUrl = $classroomModel ? route('teacher.attendance.index', ['classroom_id' => $classroomModel->id]) : url('/teacher/attendance');
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 pl-6 pr-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($code, 0, 3)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $name }}</p>
                                        <p class="text-xs text-slate-400">{{ $desc }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-3 font-mono text-xs font-bold text-slate-800">
                                {{ $code }}
                            </td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 text-xs font-semibold">
                                    {{ $className }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-xs font-medium text-slate-800">
                                {{ $room }}
                            </td>
                            <td class="py-4 px-3 text-xs font-semibold text-slate-700 font-mono">
                                {{ $studentsInClass }} Students
                            </td>
                            <td class="py-4 pl-3 pr-6 text-right">
                                <a href="{{ $actionUrl }}" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-semibold transition-colors">
                                    Attendance
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                <div class="w-10 h-10 mx-auto rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 mb-2">
                                    📖
                                </div>
                                <p class="text-sm font-medium text-slate-600">No subjects currently assigned</p>
                                <p class="text-xs text-slate-400 mt-0.5">When academic administrators assign course sections to your profile, they will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Assigned Classrooms Cards -->
    <div>
        <h3 class="text-base font-bold text-slate-900 mb-4">Assigned Classrooms</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($assignedClassrooms as $classroom)
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md font-mono">{{ $classroom->grade_level ?? 'Section' }}</span>
                            <h4 class="text-lg font-bold text-slate-900 mt-2">{{ $classroom->name }}</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Academic Year {{ $classroom->academic_year ?? date('Y') }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-lg">
                            🏫
                        </div>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>{{ $classroom->studentProfiles?->count() ?? 0 }} Students Enrolled</span>
                        <a href="{{ route('teacher.attendance.index', ['classroom_id' => $classroom->id]) }}" class="text-emerald-700 font-semibold hover:underline">Attendance &rarr;</a>
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 bg-white p-8 rounded-2xl border border-slate-200/80 text-center text-slate-400">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 mb-2">
                        🏫
                    </div>
                    <p class="text-sm font-medium text-slate-600">No classrooms assigned yet</p>
                    <p class="text-xs text-slate-400 mt-0.5">Classrooms assigned to your teaching schedule will be listed here.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
