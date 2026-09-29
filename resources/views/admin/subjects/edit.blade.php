<!-- Edit Subject Modal (Alpine.js) -->
<div 
    x-show="editSubjectModal" 
    x-cloak 
    @keydown.escape.window="editSubjectModal = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="edit-modal-title"
>
    <div class="min-h-screen px-4 text-center flex items-center justify-center py-6 sm:py-10">
        <!-- Backdrop Overlay -->
        <div 
            x-show="editSubjectModal" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="editSubjectModal = false" 
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
            aria-hidden="true"
        ></div>

        <!-- Modal Panel -->
        <div 
            x-show="editSubjectModal" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            @click.stop
            class="inline-block w-full max-w-xl text-left bg-white rounded-3xl shadow-2xl border border-slate-200/80 transform transition-all relative z-50 overflow-hidden"
        >
            <!-- Modal Header -->
            <div class="p-6 pb-4 border-b border-slate-100 flex items-start justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 id="edit-modal-title" class="text-lg font-bold text-slate-900 tracking-tight">Edit Subject Details</h3>
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-mono font-bold bg-indigo-50 text-indigo-700 border border-indigo-100" x-text="selectedSubject.code"></span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Modify course curriculum, syllabus, and credit requirements</p>
                    </div>
                </div>

                <button 
                    type="button" 
                    @click="editSubjectModal = false" 
                    class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer"
                    title="Close"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Form Body -->
            <form :action="'/admin/subjects/' + selectedSubject.id" method="POST" class="p-6 pt-4 space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Subject Title -->
                    <div class="sm:col-span-2">
                        <label for="edit_subject_name" class="block font-semibold text-slate-700 mb-1">Subject Title *</label>
                        <input 
                            type="text" 
                            id="edit_subject_name"
                            name="name"
                            x-model="selectedSubject.name"
                            required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800"
                        >
                    </div>

                    <!-- Subject Code -->
                    <div>
                        <label for="edit_subject_code" class="block font-semibold text-slate-700 mb-1">Subject Code *</label>
                        <input 
                            type="text" 
                            id="edit_subject_code"
                            name="code"
                            x-model="selectedSubject.code"
                            required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium text-slate-800"
                        >
                    </div>

                    <!-- Academic Level -->
                    <div>
                        <label for="edit_level_id" class="block font-semibold text-slate-700 mb-1">Academic Level</label>
                        <select 
                            id="edit_level_id"
                            name="level_id" 
                            x-model="selectedSubject.level_id"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800"
                        >
                            <option value="">All / General Level</option>
                            @if(isset($levels))
                                @foreach($levels as $lvl)
                                    <option value="{{ $lvl->id }}">{{ $lvl->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Target Classrooms (Multi-Select Classrooms from DB) -->
                    <div 
                        x-data="{
                            open: false,
                            search: '',
                            items: {{ json_encode($classrooms->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'grade' => $c->grade_level, 'year' => $c->academic_year])) }},
                            toggle(id) {
                                id = parseInt(id);
                                if (!Array.isArray(selectedSubject.classroom_ids)) {
                                    selectedSubject.classroom_ids = [];
                                }
                                if (selectedSubject.classroom_ids.includes(id)) {
                                    selectedSubject.classroom_ids = selectedSubject.classroom_ids.filter(x => x !== id);
                                } else {
                                    selectedSubject.classroom_ids.push(id);
                                }
                            },
                            get selectedObjects() {
                                if (!Array.isArray(selectedSubject.classroom_ids)) return [];
                                return this.items.filter(i => selectedSubject.classroom_ids.includes(i.id));
                            },
                            get filteredItems() {
                                if (!this.search.trim()) return this.items;
                                const q = this.search.toLowerCase();
                                return this.items.filter(i => i.name.toLowerCase().includes(q) || (i.grade && i.grade.toLowerCase().includes(q)));
                            }
                        }"
                        class="sm:col-span-2 relative"
                        @click.outside="open = false"
                    >
                        <div class="flex items-center justify-between mb-1">
                            <label class="block font-semibold text-slate-700">
                                Target Classrooms
                                <span class="text-xs font-normal text-slate-400 ml-1">(Assign subject to one or multiple classes)</span>
                            </label>
                            <span 
                                x-show="(selectedSubject.classroom_ids || []).length > 0" 
                                x-text="(selectedSubject.classroom_ids || []).length + ' class' + ((selectedSubject.classroom_ids || []).length > 1 ? 'es' : '') + ' assigned'"
                                class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100"
                            ></span>
                        </div>

                        <!-- Hidden Inputs for Form Submission -->
                        <template x-for="id in (selectedSubject.classroom_ids || [])" :key="id">
                            <input type="hidden" name="classrooms[]" :value="id">
                        </template>

                        <!-- Dropdown Trigger Box -->
                        <div 
                            @click="open = !open" 
                            class="min-h-[44px] w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100/60 transition flex items-center justify-between gap-2"
                        >
                            <div class="flex flex-wrap items-center gap-1.5 flex-1">
                                <template x-if="!selectedSubject.classroom_ids || selectedSubject.classroom_ids.length === 0">
                                    <span class="text-slate-400 font-normal text-xs sm:text-sm">Click to select classrooms...</span>
                                </template>
                                <template x-for="item in selectedObjects" :key="item.id">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span x-text="item.name"></span>
                                        <button 
                                            type="button" 
                                            @click.stop="toggle(item.id)"
                                            class="text-indigo-400 hover:text-indigo-700 focus:outline-none ml-0.5 cursor-pointer"
                                            title="Remove"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                            </div>
                            <svg 
                                class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" 
                                :class="{'rotate-180': open}" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <!-- Dropdown Menu -->
                        <div 
                            x-show="open" 
                            x-cloak 
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="absolute left-0 right-0 mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-2.5 space-y-2"
                        >
                            <!-- Search filter -->
                            <div class="relative">
                                <input 
                                    type="text" 
                                    x-model="search" 
                                    @click.stop 
                                    placeholder="Search classroom or grade level..." 
                                    class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                >
                                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>

                            <!-- List of classrooms from DB -->
                            <div class="max-h-48 overflow-y-auto divide-y divide-slate-100">
                                <template x-for="cls in filteredItems" :key="cls.id">
                                    <div 
                                        @click="toggle(cls.id)"
                                        class="px-2.5 py-2 hover:bg-slate-50 rounded-xl cursor-pointer flex items-center justify-between text-xs transition"
                                    >
                                        <div class="flex items-center gap-2">
                                            <div 
                                                class="w-4 h-4 rounded border flex items-center justify-center transition"
                                                :class="(selectedSubject.classroom_ids || []).includes(cls.id) ? 'bg-indigo-600 border-indigo-600 text-white' : 'border-slate-300 bg-white'"
                                            >
                                                <svg x-show="(selectedSubject.classroom_ids || []).includes(cls.id)" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="font-medium text-slate-700" x-text="cls.name"></span>
                                        </div>
                                        <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 font-semibold" x-text="cls.grade"></span>
                                    </div>
                                </template>
                                <div x-show="filteredItems.length === 0" class="py-3 text-center text-xs text-slate-400">
                                    No matching class found.
                                </div>
                            </div>
                        </div>

                        <!-- Quick-Pick Class Pills from DB -->
                        @if(isset($classrooms) && count($classrooms) > 0)
                            <div class="mt-2 flex flex-wrap items-center gap-1.5">
                                <span class="text-[11px] text-slate-400 font-medium mr-1">Quick assign:</span>
                                @foreach($classrooms as $cls)
                                    <button 
                                        type="button" 
                                        @click="toggle({{ $cls->id }})"
                                        class="text-[11px] font-medium px-2.5 py-1 rounded-lg border transition cursor-pointer"
                                        :class="(selectedSubject.classroom_ids || []).includes({{ $cls->id }}) ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100 hover:text-slate-800'"
                                    >
                                        + {{ $cls->name }}
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Description / Course Syllabus -->
                    <div class="sm:col-span-2">
                        <label for="edit_subject_description" class="block font-semibold text-slate-700 mb-1">Course Description & Syllabus Outline</label>
                        <textarea 
                            id="edit_subject_description"
                            name="description"
                            rows="3" 
                            x-model="selectedSubject.desc"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800 resize-none"
                        ></textarea>
                    </div>
                </div>

                <!-- Modal Actions Footer -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <button 
                        type="button" 
                        @click="editSubjectModal = false" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition-colors cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-xs hover:shadow transition-all duration-150 cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span>Update Subject</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
