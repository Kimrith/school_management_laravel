<!-- Add Academic Level Modal (Alpine.js) -->
<div 
    x-show="addModalOpen" 
    x-cloak 
    @keydown.escape.window="addModalOpen = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="add-level-modal-title"
>
    <div class="min-h-screen px-4 text-center flex items-center justify-center py-6 sm:py-10">
        <!-- Backdrop Overlay -->
        <div 
            x-show="addModalOpen" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="addModalOpen = false" 
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
            aria-hidden="true"
        ></div>

        <!-- Modal Panel -->
        <div 
            x-show="addModalOpen" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            @click.stop
            class="inline-block w-full max-w-lg text-left bg-white rounded-3xl shadow-2xl border border-slate-200/80 transform transition-all relative z-50 overflow-hidden"
        >
            <!-- Modal Header -->
            <div class="p-6 pb-4 border-b border-slate-100 flex items-start justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 id="add-level-modal-title" class="text-lg font-bold text-slate-900 tracking-tight">Register Academic Level</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Add a new grade stage or education tier for classroom categorization</p>
                    </div>
                </div>

                <button 
                    type="button" 
                    @click="addModalOpen = false" 
                    class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer"
                    title="Close"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Form Body -->
            <form 
                action="{{ route('admin.levels.store') }}" 
                method="POST" 
                class="p-6 pt-4 space-y-4 text-xs"
                x-data="{ levelName: '{{ old('name') }}' }"
            >
                @csrf

                <!-- Level Name -->
                <div>
                    <label for="insert_level_name" class="block font-semibold text-slate-700 mb-1">Level Name *</label>
                    <input 
                        type="text" 
                        id="insert_level_name"
                        name="name" 
                        x-model="levelName"
                        required 
                        placeholder="e.g. Grade 10, Year 1, High School" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border @error('name') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400 text-slate-800"
                    >
                    @error('name')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror

                    <!-- Quick Preset Tag Recommendations -->
                    <div class="mt-2.5">
                        <p class="text-[11px] text-slate-400 mb-1.5 font-medium">Quick suggestions:</p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach(['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12', 'Year 1', 'Year 2'] as $tag)
                                <button 
                                    type="button" 
                                    @click="levelName = '{{ $tag }}'"
                                    class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 border border-slate-200/60 transition-colors cursor-pointer"
                                >
                                    + {{ $tag }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Status Selector -->
                <div>
                    <label for="insert_level_status" class="block font-semibold text-slate-700 mb-1">Initial Status *</label>
                    <select 
                        id="insert_level_status"
                        name="status" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border @error('status') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-semibold text-slate-800"
                    >
                        <option value="Active" {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>Active - In Curriculum</option>
                        <option value="Suspended" {{ old('status') === 'Suspended' ? 'selected' : '' }}>Suspended / Archived</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Modal Actions Footer -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button 
                        type="button" 
                        @click="addModalOpen = false" 
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
                        <span>Save Level</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
