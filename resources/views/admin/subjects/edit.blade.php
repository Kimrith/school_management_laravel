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
            <form action="#" method="POST" @submit.prevent="editSubjectModal = false" class="p-6 pt-4 space-y-4 text-xs">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Subject Title -->
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Subject Title *</label>
                        <input 
                            type="text" 
                            x-model="selectedSubject.name"
                            required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800"
                        >
                    </div>

                    <!-- Subject Code -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Subject Code *</label>
                        <input 
                            type="text" 
                            x-model="selectedSubject.code"
                            required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium text-slate-800"
                        >
                    </div>

                    <!-- Academic Credits -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Academic Credits</label>
                        <select 
                            x-model="selectedSubject.credits"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-700"
                        >
                            <option value="4 Credits">4 Credits (Lecture + Lab)</option>
                            <option value="3 Credits">3 Credits (Standard Lecture)</option>
                            <option value="2 Credits">2 Credits (Seminar)</option>
                            <option value="1 Credit">1 Credit (Workshop)</option>
                        </select>
                    </div>

                    <!-- Assigned Faculty -->
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Lead Instructor(s)</label>
                        <input 
                            type="text" 
                            x-model="selectedSubject.teachers"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800"
                        >
                    </div>

                    <!-- Description / Course Syllabus -->
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Course Description & Syllabus Outline</label>
                        <textarea 
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
