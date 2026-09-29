@if($classrooms->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($classrooms as $class)
            @php
                $enrolled = $class->student_profiles_count ?? 0;
                $capacity = max(1, $class->capacity ?? 40);
                $percent = min(100, round(($enrolled / $capacity) * 100));
                $isSuspended = ($class->status === 'suspended');
                $headTeacher = $class->teachers->first();

                // Grade badge color schemes
                $gradeBadge = match(true) {
                    str_contains($class->grade_level, '12') => 'bg-purple-50 text-purple-700 border-purple-200/80',
                    str_contains($class->grade_level, '11') => 'bg-sky-50 text-sky-700 border-sky-200/80',
                    str_contains($class->grade_level, '10') => 'bg-indigo-50 text-indigo-700 border-indigo-200/80',
                    default => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
                };
            @endphp

            <div 
                class="bg-white rounded-3xl p-6 border transition-all duration-200 flex flex-col justify-between group shadow-xs hover:shadow-md {{ $isSuspended ? 'border-amber-200/90 bg-amber-50/20' : 'border-slate-200/80 hover:border-slate-300' }}"
            >
                <div>
                    <!-- Card Top Header: Grade Badge, Room, and Status -->
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center flex-wrap gap-1.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $gradeBadge }}">
                                {{ $class->level->name ?? $class->grade_level }}
                            </span>
                            
                            @if($class->room)
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-600 bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200/60">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    {{ $class->room }}
                                </span>
                            @endif
                        </div>

                        <div>
                            @if($isSuspended)
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700 bg-amber-100/80 px-2.5 py-0.5 rounded-full border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Archived
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Classroom Name & Academic Year -->
                    <div class="mt-3.5 flex items-start justify-between gap-2">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors leading-snug">
                                {{ $class->name }}
                            </h2>
                            <p class="text-xs text-slate-400 font-mono mt-0.5">
                                Academic Year: <span class="text-slate-600 font-semibold">{{ $class->academic_year }}</span>
                            </p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-center shrink-0 text-slate-600 group-hover:bg-indigo-50 group-hover:border-indigo-100 group-hover:text-indigo-600 transition-colors">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Head Teacher / Advisor -->
                    <div class="mt-3 py-2 px-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400 font-medium">Head Teacher:</span>
                        <div class="flex items-center gap-1.5 font-semibold text-slate-700">
                            @if($headTeacher)
                                <div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-bold">
                                    {{ strtoupper(substr($headTeacher->name, 0, 1)) }}
                                </div>
                                <span class="truncate max-w-[140px]">{{ $headTeacher->name }}</span>
                            @else
                                <span class="text-slate-400 italic font-normal">Not assigned</span>
                            @endif
                        </div>
                    </div>

                    <!-- Enrollment Progress & Capacity -->
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="text-slate-500 font-medium">
                                Enrolled: <strong class="text-slate-900 font-bold">{{ $enrolled }}</strong> / {{ $capacity }}
                            </span>
                            <span class="font-mono font-bold {{ $percent >= 100 ? 'text-rose-600' : ($percent >= 85 ? 'text-amber-600' : 'text-indigo-600') }}">
                                {{ $percent }}%
                            </span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div 
                                class="h-full rounded-full transition-all duration-500 {{ $percent >= 100 ? 'bg-rose-500' : ($percent >= 85 ? 'bg-amber-500' : 'bg-gradient-to-r from-indigo-500 to-indigo-600') }}" 
                                style="width: {{ $percent }}%"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Card Bottom Actions Footer -->
                <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between text-xs">
                    <a 
                        href="{{ route('admin.students.index', ['classroom_id' => $class->id]) }}" 
                        class="inline-flex items-center gap-1 font-semibold text-indigo-600 hover:text-indigo-800 transition-colors"
                    >
                        <span>View Students</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>

                    <div class="flex items-center gap-1">
                        <!-- Edit Button (triggers Alpine edit modal or page) -->
                        <button 
                            type="button" 
                            @click="selectedClassroom = {
                                id: '{{ $class->id }}',
                                name: '{{ addslashes($class->name) }}',
                                level_id: '{{ $class->level_id ?? '' }}',
                                grade_level: '{{ addslashes($class->grade_level) }}',
                                academic_year: '{{ addslashes($class->academic_year) }}',
                                room: '{{ addslashes($class->room ?? '') }}',
                                capacity: '{{ $class->capacity ?? 40 }}',
                                status: '{{ $class->status ?? 'active' }}',
                                description: '{{ addslashes($class->description ?? '') }}',
                                teacher_id: '{{ $headTeacher?->id ?? '' }}'
                            }; editModalOpen = true;"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors cursor-pointer"
                            title="Edit Classroom"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </button>

                        <!-- Full Edit Page Link -->
                        <a 
                            href="{{ route('admin.classes.edit', $class) }}" 
                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                            title="Full Page Editor"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                        </a>

                        <!-- Archive / Reinstate Button -->
                        <form action="{{ route('admin.classes.toggle-status', $class) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button 
                                type="submit" 
                                class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors cursor-pointer"
                                title="{{ $isSuspended ? 'Reinstate Classroom' : 'Archive Classroom' }}"
                            >
                                @if($isSuspended)
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                    </svg>
                                @endif
                            </button>
                        </form>

                        <!-- Delete Button (triggers delete modal) -->
                        <button 
                            type="button" 
                            @click="selectedClassroom = {
                                id: '{{ $class->id }}',
                                name: '{{ addslashes($class->name) }}'
                            }; deleteModalOpen = true;"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                            title="Delete Classroom"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($classrooms->hasPages())
        <div class="mt-6">
            {{ $classrooms->links() }}
        </div>
    @endif
@else
    <!-- Clean Empty State -->
    <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-xs max-w-lg mx-auto">
        <div class="w-16 h-16 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-900">No Classrooms Found</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">
            @if(request('search') || request('status') || request('grade'))
                No classrooms match your current search criteria or active filters.
            @else
                Get started by creating grade-level classrooms, setting student capacities, and assigning head teachers.
            @endif
        </p>
        <div class="mt-5 flex items-center justify-center gap-3">
            @if(request('search') || request('status') || request('grade'))
                <a 
                    href="{{ route('admin.classes.index') }}" 
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors"
                >
                    Reset Filters
                </a>
            @endif
            <button 
                type="button" 
                @click="addModalOpen = true"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs hover:shadow transition-all cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Classroom</span>
            </button>
        </div>
    </div>
@endif
