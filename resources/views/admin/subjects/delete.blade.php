<!-- Delete Subject Confirmation Modal (Alpine.js) -->
<div 
    x-show="deleteSubjectModal" 
    x-cloak 
    @keydown.escape.window="deleteSubjectModal = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="delete-modal-title"
>
    <div class="min-h-screen px-4 text-center flex items-center justify-center py-6 sm:py-10">
        <!-- Backdrop Overlay -->
        <div 
            x-show="deleteSubjectModal" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="deleteSubjectModal = false" 
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
            aria-hidden="true"
        ></div>

        <!-- Modal Panel -->
        <div 
            x-show="deleteSubjectModal" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            @click.stop
            class="inline-block w-full max-w-md text-left bg-white rounded-3xl shadow-2xl border border-slate-200/80 transform transition-all relative z-50 overflow-hidden"
        >
            <!-- Wrap content in a form pointing to your Laravel route -->
            <form :action="`/admin/subjects/${selectedSubject.id}`" method="POST">
                @csrf
                @method('DELETE')

                <div class="p-6">
                    <!-- Danger Icon -->
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mb-4 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </div>

                    <!-- Text Content -->
                    <h3 id="delete-modal-title" class="text-lg font-bold text-slate-900 tracking-tight">Delete Academic Subject</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        Are you sure you want to permanently delete 
                        <span class="font-bold text-slate-900" x-text="selectedSubject.name"></span> 
                        (<span class="font-mono font-bold text-indigo-600" x-text="selectedSubject.code"></span>)?
                    </p>

                    <!-- Warning Notice -->
                    <div class="mt-4 p-3 bg-rose-50/70 border border-rose-100 rounded-xl flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 12.376Z" />
                        </svg>
                        <p class="text-[11px] text-rose-800 leading-relaxed font-medium">
                            This action cannot be undone. Teaching allocations and syllabus materials mapped to this subject will be removed.
                        </p>
                    </div>

                    <!-- Modal Actions -->
                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button 
                            type="button" 
                            @click="deleteSubjectModal = false" 
                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition-colors cursor-pointer"
                        >
                            Cancel
                        </button>

                        <button 
                            type="submit" 
                            class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-xs hover:shadow transition-all cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                            <span>Yes, Delete Course</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>