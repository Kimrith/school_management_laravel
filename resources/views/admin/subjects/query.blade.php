<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($subjectsList as $subj)
        <div 
            x-show="!searchQuery || '{{ strtolower(addslashes($subj['name'] . ' ' . $subj['code'] . ' ' . $subj['desc'])) }}'.includes(searchQuery.toLowerCase())"
            class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:border-slate-300 hover:shadow-md transition-all duration-150 flex flex-col justify-between group"
        >
            <div>
                <!-- Card Header: Code Badge & Credit Info -->
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-indigo-50 text-indigo-700 text-xs font-mono font-bold border border-indigo-100/80">
                            {{ $subj['code'] }}
                        </span>
                        <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-lg">
                            {{ $subj['credits'] }}
                        </span>
                    </div>
                    <span class="text-[11px] text-emerald-600 font-semibold bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-100">
                        Active
                    </span>
                </div>

                <!-- Title & Description -->
                <h2 class="text-base font-bold text-slate-900 mt-3.5 leading-snug group-hover:text-indigo-600 transition-colors">
                    {{ $subj['name'] }}
                </h2>
                <p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
                    {{ $subj['desc'] }}
                </p>

                <!-- Academic Metadata -->
                <div class="mt-5 pt-3.5 border-t border-slate-100 space-y-2 text-xs">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-slate-400 font-medium">Assigned Faculty:</span>
                        <span class="text-slate-800 font-semibold">{{ implode(', ', $subj['teachers']) }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-slate-400 font-medium">Classrooms:</span>
                        <div class="flex flex-wrap gap-1 justify-end">
                            @foreach($subj['classes'] as $cls)
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700">
                                    {{ $cls }}
                                </span>
                            @endforeach
                        </div>
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
                            name: '{{ addslashes($subj['name']) }}',
                            code: '{{ addslashes($subj['code']) }}',
                            desc: '{{ addslashes($subj['desc']) }}',
                            credits: '{{ addslashes($subj['credits']) }}',
                            teachers: '{{ addslashes(implode(', ', $subj['teachers'])) }}',
                            classes: '{{ addslashes(implode(', ', $subj['classes'])) }}'
                        }; editSubjectModal = true;"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 font-semibold transition-colors cursor-pointer"
                        title="Edit Course"
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
                            name: '{{ addslashes($subj['name']) }}',
                            code: '{{ addslashes($subj['code']) }}'
                        }; deleteSubjectModal = true;"
                        class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                        title="Delete Course"
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