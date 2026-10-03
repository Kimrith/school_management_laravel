<!-- Schedule Exam & Assign Subjects Modal -->
<div x-show="addExamModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <div x-show="addExamModal" @click="addExamModal = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs"></div>
        <div 
            x-show="addExamModal" 
            x-data="{
                multiSubjectMode: false,
                selectAllSubjects() {
                    const checkboxes = $el.querySelectorAll('input[name=\'subject_ids[]\']');
                    checkboxes.forEach(cb => cb.checked = true);
                },
                deselectAllSubjects() {
                    const checkboxes = $el.querySelectorAll('input[name=\'subject_ids[]\']');
                    checkboxes.forEach(cb => cb.checked = false);
                }
            }"
            class="relative bg-white rounded-2xl max-w-lg w-full p-6 text-left shadow-xl border border-slate-200/80 my-8 z-10"
        >
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-900" x-text="titleMode === 'existing' && modalTitle ? 'Assign Subject to \'' + modalTitle + '\'' : 'Schedule Examination & Assign Subjects'"></h3>
                    <p class="text-xs text-slate-500 mt-0.5">Assign subjects and test papers to your examination title.</p>
                </div>
                <button @click="addExamModal = false" class="p-1 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('admin.exams.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf

                <!-- Title Mode Selector (New Title vs. Existing Exam Title) -->
                @if(isset($existingTitles) && $existingTitles->isNotEmpty())
                    <div class="flex items-center gap-4 p-1.5 bg-slate-100/80 rounded-xl text-xs font-semibold text-slate-600">
                        <button 
                            type="button" 
                            @click="titleMode = 'new'"
                            :class="titleMode === 'new' ? 'bg-white text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="flex-1 py-1.5 text-center rounded-lg transition-all cursor-pointer"
                        >
                            + New Exam Title
                        </button>
                        <button 
                            type="button" 
                            @click="titleMode = 'existing'"
                            :class="titleMode === 'existing' ? 'bg-white text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="flex-1 py-1.5 text-center rounded-lg transition-all cursor-pointer"
                        >
                            Use Existing Title
                        </button>
                    </div>
                @endif
                
                <!-- Exam Title Input / Select -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Exam Title</label>
                    <template x-if="titleMode === 'new'">
                        <input 
                            type="text" 
                            name="title" 
                            x-model="modalTitle"
                            placeholder="e.g. Final Examination 2026, Midterm Term 1" 
                            class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20" 
                            required
                        >
                    </template>
                    <template x-if="titleMode === 'existing'">
                        <select 
                            name="title" 
                            x-model="modalTitle" 
                            class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                            required
                        >
                            <option value="">Select Existing Exam Title</option>
                            @foreach($existingTitles as $examTitle)
                                <option value="{{ $examTitle }}">{{ $examTitle }}</option>
                            @endforeach
                        </select>
                    </template>
                </div>

                <!-- Classroom Select -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Classroom</label>
                    <select 
                        name="classroom_id" 
                        x-model="modalClassroomId"
                        class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20" 
                        required
                    >
                        <option value="">Select Classroom</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}">
                                {{ $classroom->name }} ({{ $classroom->grade_level ?? 'Class' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Subject Assignment Mode Toggle -->
                <div class="pt-1">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-slate-700">Assign Subject(s)</label>
                        <button 
                            type="button" 
                            @click="multiSubjectMode = !multiSubjectMode"
                            class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-800 transition-colors"
                        >
                            <span x-text="multiSubjectMode ? 'Switch to Single Subject' : '+ Assign Multiple Subjects at Once'"></span>
                        </button>
                    </div>

                    <!-- Single Subject Dropdown -->
                    <div x-show="!multiSubjectMode">
                        <select 
                            name="subject_id" 
                            :required="!multiSubjectMode"
                            class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        >
                            <option value="">Select Any Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">
                                    {{ $subject->name }} ({{ $subject->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Multiple Subjects Checkbox List -->
                    <div x-show="multiSubjectMode" x-cloak class="border border-slate-200 rounded-xl p-3 bg-slate-50/50">
                        <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-200/70 text-xs">
                            <span class="text-slate-500 font-medium">Select subjects to include:</span>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="selectAllSubjects()" class="text-indigo-600 hover:underline font-semibold text-[11px]">Select All</button>
                                <span class="text-slate-300">&bull;</span>
                                <button type="button" @click="deselectAllSubjects()" class="text-slate-500 hover:underline font-semibold text-[11px]">Clear</button>
                            </div>
                        </div>

                        <div class="max-h-40 overflow-y-auto space-y-1.5 pr-1">
                            @foreach($subjects as $subject)
                                <label class="flex items-center gap-2.5 p-1.5 rounded-lg hover:bg-white text-xs text-slate-700 cursor-pointer transition-colors">
                                    <input 
                                        type="checkbox" 
                                        name="subject_ids[]" 
                                        value="{{ $subject->id }}"
                                        class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4 border-slate-300 cursor-pointer"
                                    >
                                    <span class="font-medium text-slate-800">{{ $subject->name }}</span>
                                    <span class="text-[11px] text-slate-400 font-mono">({{ $subject->code }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Exam Date & Total Marks Grid -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Exam Date</label>
                        <input 
                            type="date" 
                            name="exam_date" 
                            value="{{ old('exam_date', now()->format('Y-m-d')) }}" 
                            class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none font-mono" 
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Total Marks</label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="total_marks" 
                            value="{{ old('total_marks', '100.00') }}" 
                            class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none font-mono font-semibold" 
                            required
                        >
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="addExamModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 rounded-xl border border-slate-200 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xs transition-colors cursor-pointer">
                        Schedule & Assign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>