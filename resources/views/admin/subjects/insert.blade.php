<!-- Add Subject Modal (Alpine.js) -->
<div 
    x-show="addSubjectModal" 
    x-cloak 
    @keydown.escape.window="addSubjectModal = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="add-modal-title"
>
    <div class="min-h-screen px-4 text-center flex items-center justify-center py-6 sm:py-10">
        <!-- Backdrop Overlay -->
        <div 
            x-show="addSubjectModal" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="addSubjectModal = false" 
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
            aria-hidden="true"
        ></div>

        <!-- Modal Panel -->
        <div 
            x-show="addSubjectModal" 
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
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div>
                        <h3 id="add-modal-title" class="text-lg font-bold text-slate-900 tracking-tight">Register New Academic Subject</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Define course syllabus, subject code, and academic units</p>
                    </div>
                </div>

                <button 
                    type="button" 
                    @click="addSubjectModal = false" 
                    class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer"
                    title="Close"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Form Body -->
            <form action="#" method="POST" @submit.prevent="addSubjectModal = false" class="p-6 pt-4 space-y-4 text-xs">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Subject Name -->
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Subject Title *</label>
                        <input 
                            type="text" 
                            required 
                            placeholder="e.g. Cloud Computing & Distributed Systems" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400"
                        >
                    </div>

                    <!-- Subject Code -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Subject Code *</label>
                        <input 
                            type="text" 
                            required 
                            placeholder="e.g. CLD401" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium placeholder:text-slate-400"
                        >
                    </div>

                    <!-- Academic Credits -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Academic Credits</label>
                        <select class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-700">
                            <option value="3 Credits">3 Credits (Standard Lecture)</option>
                            <option value="4 Credits">4 Credits (Lecture + Lab)</option>
                            <option value="2 Credits">2 Credits (Seminar)</option>
                            <option value="1 Credit">1 Credit (Workshop)</option>
                        </select>
                    </div>

<!-- Assigned Faculty -->
<div>
    <label class="block text-xs font-semibold text-slate-700 mb-1">Lead Instructor</label>
    <select 
        name="teacher_id" 
        x-model="selectedSubject.teacher_id" 
        class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-700"
    >
        <option value="">Select Instructor...</option>
        @foreach($teachers as $teacher)
            <option value="{{ $teacher->id }}">
                {{ $teacher->user->name ?? 'Unknown Teacher' }}
                @if($teacher->specialization)
                    ({{ $teacher->specialization }})
                @endif
            </option>
        @endforeach
    </select>
</div>

                    <!-- Class Allocation -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Target Classroom</label>
                        <select class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-700">
                            <option value="">Select Classroom...</option>
                            <option value="Grade 10-A">Grade 10-A</option>
                            <option value="Grade 11-B">Grade 11-B</option>
                            <option value="Grade 12-A">Grade 12-A</option>
                            <option value="Grade 9-C">Grade 9-C</option>
                        </select>
                    </div>

                    <!-- Description / Course Syllabus -->
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Course Description & Syllabus Outline</label>
                        <textarea 
                            rows="3" 
                            placeholder="Provide an overview of course topics, prerequisites, and learning objectives..." 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400 resize-none"
                        ></textarea>
                    </div>
                </div>

                <!-- Modal Actions Footer -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button 
                        type="button" 
                        @click="addSubjectModal = false" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition-colors cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-xs hover:shadow transition-all duration-150 cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Save Subject</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>