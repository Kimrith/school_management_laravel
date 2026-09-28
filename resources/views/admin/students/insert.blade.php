<!-- Add / Register Student Modal (Alpine.js) -->
<div 
    x-show="addModalOpen" 
    x-cloak 
    @keydown.escape.window="addModalOpen = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-title"
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
            class="inline-block w-full max-w-2xl text-left bg-white rounded-3xl shadow-2xl border border-slate-200/80 transform transition-all relative z-50 overflow-hidden"
        >
            <!-- Modal Header -->
            <div class="p-6 pb-4 border-b border-slate-100 flex items-start justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 id="modal-title" class="text-lg font-bold text-slate-900 tracking-tight">Register New Student</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Create a student profile and automatic login credentials</p>
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

            <!-- Form Content -->
            <form action="{{ route('admin.students.store') }}" method="POST" class="p-6 pt-4 space-y-4 text-xs">
                @csrf

                <!-- Credentials Info Banner -->
                <div class="bg-indigo-50/70 border border-indigo-100/90 rounded-2xl p-3 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-indigo-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>
                    <p class="text-[11px] text-indigo-900 leading-relaxed">
                        Default initial password will be set to <span class="font-mono font-bold text-indigo-700 bg-indigo-100/80 px-1 py-0.5 rounded">password123</span>. The student can log in and change it later.
                    </p>
                </div>

                <!-- Section: Student Information -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Student Full Name -->
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Student Full Name *</label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            placeholder="e.g. Sovan Meas" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('name') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400"
                        >
                        @error('name')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Email Address (Login) *</label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            placeholder="student@school.edu" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('email') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400"
                        >
                        @error('email')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Student Code -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Student Code *</label>
                        <input 
                            type="text" 
                            name="student_code" 
                            value="{{ old('student_code') }}" 
                            required 
                            placeholder="e.g. STU-1006" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('student_code') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium placeholder:text-slate-400"
                        >
                        @error('student_code')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Classroom Assignment -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Classroom</label>
                        <select 
                            name="classroom_id" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('classroom_id') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-700"
                        >
                            <option value="">Select Classroom...</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }} (Grade {{ $classroom->grade_level }})
                                </option>
                            @endforeach
                        </select>
                        @error('classroom_id')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Gender *</label>
                        <select 
                            name="gender" 
                            required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('gender') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-700"
                        >
                            <option value="">Select Gender...</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Date of Birth</label>
                        <input 
                            type="date" 
                            name="date_of_birth" 
                            value="{{ old('date_of_birth') }}" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('date_of_birth') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium text-slate-700"
                        >
                        @error('date_of_birth')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent / Guardian Name -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Parent / Guardian Name</label>
                        <input 
                            type="text" 
                            name="parent_name" 
                            value="{{ old('parent_name') }}" 
                            placeholder="e.g. Socheat Meas" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('parent_name') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400"
                        >
                        @error('parent_name')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Contact Phone -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Parent Contact Phone</label>
                        <input 
                            type="text" 
                            name="parent_phone" 
                            value="{{ old('parent_phone') }}" 
                            placeholder="e.g. 012 345 678" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('parent_phone') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium placeholder:text-slate-400"
                        >
                        @error('parent_phone')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Home Address -->
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Home Address</label>
                        <textarea 
                            name="address" 
                            rows="2" 
                            placeholder="e.g. #123, St. 271, Sangkat Boeung Tumpun, Khan Meanchey, Phnom Penh" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('address') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400 resize-none"
                        >{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
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
                        <span>Save Student</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>