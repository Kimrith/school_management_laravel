<!-- Add Classroom Modal (Alpine.js) -->
<div 
    x-show="addModalOpen" 
    x-cloak 
    @keydown.escape.window="addModalOpen = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="add-modal-title"
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
            class="inline-block w-full max-w-xl text-left bg-white rounded-3xl shadow-2xl border border-slate-200/80 transform transition-all relative z-50 overflow-hidden"
        >
            <!-- Modal Header -->
            <div class="p-6 pb-4 border-b border-slate-100 flex items-start justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 id="add-modal-title" class="text-lg font-bold text-slate-900 tracking-tight">Create New Classroom</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Register a grade section, assign physical room, and set capacity limits</p>
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
            <form action="{{ route('admin.classes.store') }}" method="POST" class="p-6 pt-4 space-y-4 text-xs">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Classroom Name -->
                    <div class="sm:col-span-2">
                        <label for="insert_name" class="block font-semibold text-slate-700 mb-1">Classroom Name *</label>
                        <input 
                            type="text" 
                            id="insert_name"
                            name="name" 
                            value="{{ old('name') }}"
                            required 
                            placeholder="e.g. Grade 10-A" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('name') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400"
                        >
                        @error('name')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grade Level -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="insert_grade_level" class="block font-semibold text-slate-700">Grade Level *</label>
                            <a href="{{ route('admin.levels.index') }}" target="_blank" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-700 hover:underline">
                                + Manage Levels
                            </a>
                        </div>
                        <select 
                            id="insert_grade_level"
                            name="grade_level" 
                            required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('grade_level') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800"
                        >
                            <option value="">Select Grade Level..</option>
                            @if(isset($levels) && $levels->isNotEmpty())
                                @foreach($levels as $lvl)
                                    <option value="{{ $lvl->name }}" {{ old('grade_level') == $lvl->name ? 'selected' : '' }}>{{ $lvl->name }}</option>
                                @endforeach
                            @else
                                @foreach(['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'] as $lvl)
                                    <option value="{{ $lvl }}" {{ old('grade_level') == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('grade_level')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Academic Year -->
                    <div>
                        <label for="insert_academic_year" class="block font-semibold text-slate-700 mb-1">Academic Year *</label>
                        <input 
                            type="text" 
                            id="insert_academic_year"
                            name="academic_year" 
                            value="{{ old('academic_year', '2025-2026') }}"
                            required 
                            placeholder="e.g. 2025-2026" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('academic_year') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium placeholder:text-slate-400"
                        >
                        @error('academic_year')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Physical Room / Location -->
                    <div>
                        <label for="insert_room" class="block font-semibold text-slate-700 mb-1">Room / Location</label>
                        <input 
                            type="text" 
                            id="insert_room"
                            name="room" 
                            value="{{ old('room') }}"
                            placeholder="e.g. Room 301 (Bldg B)" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400"
                        >
                    </div>

                    <!-- Maximum Student Capacity -->
                    <div>
                        <label for="insert_capacity" class="block font-semibold text-slate-700 mb-1">Student Capacity</label>
                        <input 
                            type="number" 
                            id="insert_capacity"
                            name="capacity" 
                            value="{{ old('capacity', 40) }}"
                            min="1" 
                            max="200"
                            placeholder="40" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium placeholder:text-slate-400"
                        >
                    </div>

                    <!-- Head Teacher / Class Advisor -->
                    <div class="sm:col-span-2">
                        <label for="insert_teacher_id" class="block font-semibold text-slate-700 mb-1">
                            Head Teacher / Advisor
                            <span class="text-xs font-normal text-slate-400 ml-1">(Optional)</span>
                        </label>
                        <select 
                            id="insert_teacher_id"
                            name="teacher_id" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-800"
                        >
                            <option value="">No head teacher assigned</option>
                            @if(isset($teachers))
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }} ({{ $teacher->email }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Description / Notes -->
                    <div class="sm:col-span-2">
                        <label for="insert_description" class="block font-semibold text-slate-700 mb-1">
                            Notes / Section Details
                            <span class="text-xs font-normal text-slate-400 ml-1">(Optional)</span>
                        </label>
                        <textarea 
                            id="insert_description"
                            name="description" 
                            rows="2" 
                            placeholder="Add notes about special program tracks, room equipment, or laboratory setup..." 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400 resize-none"
                        >{{ old('description') }}</textarea>
                    </div>
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
                        <span>Save Classroom</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
