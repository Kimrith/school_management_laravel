@if(count($subjectsList) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($subjectsList as $subj)
            <div 
                x-show="!searchQuery || '{{ strtolower(addslashes($subj['name'] . ' ' . $subj['code'] . ' ' . ($subj['level_name'] ?? '') . ' ' . implode(' ', $subj['classes'] ?? []) . ' ' . ($subj['desc'] ?? '')))}}'.includes(searchQuery.toLowerCase())"
                class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:border-slate-300 hover:shadow-md transition-all duration-150 flex flex-col justify-between group"
            >
                <div>
                    <!-- Card Header: Code Badge & Level & Classroom / Status Badge -->
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center flex-wrap gap-1.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-indigo-50 text-indigo-700 text-xs font-mono font-bold border border-indigo-100/80">
                                {{ $subj['code'] }}
                            </span>
                            @if(!empty($subj['level_name']) && $subj['level_name'] !== 'All Levels')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-purple-50 text-purple-700 text-[11px] font-semibold border border-purple-100">
                                    {{ $subj['level_name'] }}
                                </span>
                            @endif
                            @if(!empty($subj['classes']) && count($subj['classes']) > 0)
                                @if(count($subj['classes']) === 1)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200/60">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.905 59.905 0 0 1 12 3.493a59.902 59.902 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                                        </svg>
                                        {{ $subj['classes'][0] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-indigo-700 bg-indigo-50/70 px-2 py-0.5 rounded-lg border border-indigo-100">
                                        <svg class="w-3 h-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ count($subj['classes']) }} Classes
                                    </span>
                                @endif
                            @endif
                        </div>
                        <span class="text-[11px] text-emerald-600 font-semibold bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-100 shrink-0">
                            Active
                        </span>
                    </div>

                    <!-- Title & Description -->
                    <h2 class="text-base font-bold text-slate-900 mt-3.5 leading-snug group-hover:text-indigo-600 transition-colors">
                        {{ $subj['name'] }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
                        {{ $subj['desc'] ?: 'No course syllabus description provided for this subject.' }}
                    </p>

                    <!-- Academic Metadata -->
                    <div class="mt-5 pt-3.5 border-t border-slate-100 space-y-2.5 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-slate-400 font-medium">Academic Level:</span>
                            <span class="text-slate-800 font-semibold">{{ $subj['level_name'] ?? 'All Levels' }}</span>
                        </div>
                        <div class="flex items-start justify-between gap-2">
                            <span class="text-slate-400 font-medium shrink-0 pt-0.5">Assigned Classes:</span>
                            <div class="text-right flex flex-wrap justify-end gap-1 max-w-[70%]">
                                @forelse($subj['classes'] ?? [] as $clsName)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200/60">
                                        {{ $clsName }}
                                    </span>
                                @empty
                                    <span class="text-slate-400 italic">Not Assigned</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-slate-400 font-medium">Subject Code:</span>
                            <span class="text-slate-700 font-mono font-medium">{{ $subj['code'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card Actions -->
                <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-[11px] text-slate-400 font-medium">Core Curriculum</span>

                    <div class="flex items-center gap-1.5">
                        <!-- Edit Button -->
                        <button 
                            type="button" 
                            @click="selectedSubject = {
                                id: '{{ $subj['id'] }}',
                                name: '{{ addslashes($subj['name']) }}',
                                code: '{{ addslashes($subj['code']) }}',
                                level_id: '{{ $subj['level_id'] ?? '' }}',
                                level_name: '{{ addslashes($subj['level_name'] ?? '') }}',
                                classroom_ids: {{ json_encode($subj['classroom_ids'] ?? []) }},
                                classroom_id: '{{ $subj['classroom_id'] ?? '' }}',
                                classroom_name: '{{ addslashes($subj['classroom_name'] ?? '') }}',
                                desc: '{{ addslashes($subj['desc'] ?? '') }}'
                            }; editSubjectModal = true;"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 font-semibold transition-colors cursor-pointer"
                            title="Edit Subject"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            <span>Edit</span>
                        </button>

                        <!-- Delete Button -->
                        <button 
                            type="button" 
                            @click="selectedSubject = {
                                id: '{{ $subj['id'] }}',
                                name: '{{ addslashes($subj['name']) }}',
                                code: '{{ addslashes($subj['code']) }}'
                            }; deleteSubjectModal = true;"
                            class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                            title="Delete Subject"
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
    <div class="mt-6 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        @include('share.pagination', ['paginator' => $subjects])
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-xs max-w-lg mx-auto">
        <div class="w-16 h-16 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-900">No Academic Subjects Registered</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">Start building the curriculum by adding subjects with codes, classrooms, and course descriptions.</p>
        <button 
            type="button" 
            @click="addSubjectModal = true"
            class="mt-5 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs hover:shadow transition-all cursor-pointer"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Register Subject</span>
        </button>
    </div>
@endif