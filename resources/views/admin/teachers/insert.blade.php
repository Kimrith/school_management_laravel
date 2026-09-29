@php
    $subjects = $subjects ?? \App\Models\Subject::orderBy('name')->get();
    $classrooms = $classrooms ?? \App\Models\Classroom::orderBy('name')->get();
@endphp

<!-- Add / Register Teacher Modal (Alpine.js) -->
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
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-5.25 6.557c-.859 0-1.5-.641-1.5-1.5v-3.675" />
                        </svg>
                    </div>
                    <div>
                        <h3 id="modal-title" class="text-lg font-bold text-slate-900 tracking-tight">Register New Faculty Member</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Create a teacher profile and academic login credentials</p>
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
            <form action="{{ route('admin.teachers.store') }}" method="POST" class="p-6 pt-4 space-y-4 text-xs">
                @csrf

                <!-- Credentials Info Banner -->
                <div class="bg-indigo-50/70 border border-indigo-100/90 rounded-2xl p-3 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-indigo-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>
                    <p class="text-[11px] text-indigo-900 leading-relaxed">
                        Default initial password will be set to <span class="font-mono font-bold text-indigo-700 bg-indigo-100/80 px-1 py-0.5 rounded">password123</span>. The instructor can log in and update their password at any time.
                    </p>
                </div>

                <!-- Section: Faculty Information -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Faculty Full Name -->
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Full Name *</label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            placeholder="e.g. Prof. Virak Meas" 
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
                            placeholder="faculty@school.edu" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('email') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400"
                        >
                        @error('email')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Contact Phone</label>
                        <input 
                            type="text" 
                            name="phone" 
                            value="{{ old('phone') }}" 
                            placeholder="012 345 678" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('phone') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono font-medium placeholder:text-slate-400"
                        >
                        @error('phone')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Qualification -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Degree / Qualification</label>
                        <input 
                            type="text" 
                            name="qualification" 
                            value="{{ old('qualification') }}" 
                            placeholder="e.g. Master of Computer Science" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('qualification') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-medium placeholder:text-slate-400"
                        >
                        @error('qualification')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Specialization / Department (Multi-Select from DB) -->
                    <div 
                        x-data="{
                            open: false,
                            selected: {{ json_encode(old('specializations', old('specialization') ? array_filter(array_map('trim', explode(',', old('specialization')))) : [])) }},
                            search: '',
                            items: {{ json_encode($subjects->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'code' => $s->code])) }},
                            toggle(name) {
                                if (this.selected.includes(name)) {
                                    this.selected = this.selected.filter(item => item !== name);
                                } else {
                                    this.selected.push(name);
                                }
                            },
                            get filteredItems() {
                                if (!this.search.trim()) return this.items;
                                const q = this.search.toLowerCase();
                                return this.items.filter(i => i.name.toLowerCase().includes(q) || i.code.toLowerCase().includes(q));
                            }
                        }"
                        class="sm:col-span-2 relative"
                        @click.outside="open = false"
                    >
                        <div class="flex items-center justify-between mb-1">
                            <label class="block font-semibold text-slate-700">
                                Specialization / Department
                                <span class="text-xs font-normal text-slate-400 ml-1">(Assign one or more from curriculum)</span>
                            </label>
                            <span 
                                x-show="selected.length > 0" 
                                x-text="selected.length + ' assigned'"
                                class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100"
                            ></span>
                        </div>

                        <!-- Hidden Inputs for Form Submission -->
                        <template x-for="val in selected" :key="val">
                            <input type="hidden" name="specializations[]" :value="val">
                        </template>
                        <input type="hidden" name="specialization" :value="selected.join(', ')">

                        <!-- Dropdown Trigger Box -->
                        <div 
                            @click="open = !open" 
                            class="min-h-[44px] w-full px-3 py-2 bg-slate-50 border @error('specialization') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl cursor-pointer hover:bg-slate-100/60 transition flex items-center justify-between gap-2"
                        >
                            <div class="flex flex-wrap items-center gap-1.5 flex-1">
                                <template x-if="selected.length === 0">
                                    <span class="text-slate-400 font-normal text-xs sm:text-sm">Click to select specializations or departments...</span>
                                </template>
                                <template x-for="(item, idx) in selected" :key="idx">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        <span x-text="item"></span>
                                        <button 
                                            type="button" 
                                            @click.stop="toggle(item)"
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
                                    placeholder="Search department or subject code..." 
                                    class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                >
                                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>

                            <!-- List of departments/subjects from DB -->
                            <div class="max-h-48 overflow-y-auto divide-y divide-slate-100">
                                <template x-for="subj in filteredItems" :key="subj.id">
                                    <div 
                                        @click="toggle(subj.name)"
                                        class="px-2.5 py-2 hover:bg-slate-50 rounded-xl cursor-pointer flex items-center justify-between text-xs transition"
                                    >
                                        <div class="flex items-center gap-2">
                                            <div 
                                                class="w-4 h-4 rounded border flex items-center justify-center transition"
                                                :class="selected.includes(subj.name) ? 'bg-indigo-600 border-indigo-600 text-white' : 'border-slate-300 bg-white'"
                                            >
                                                <svg x-show="selected.includes(subj.name)" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="font-medium text-slate-700" x-text="subj.name"></span>
                                        </div>
                                        <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 font-semibold" x-text="subj.code"></span>
                                    </div>
                                </template>
                                <div x-show="filteredItems.length === 0" class="py-3 text-center text-xs text-slate-400">
                                    No matching department or subject found.
                                </div>
                            </div>
                        </div>

                        <!-- Quick-Pick Pills from DB -->
                        @if($subjects->isNotEmpty())
                            <div class="mt-2 flex flex-wrap items-center gap-1.5">
                                <span class="text-[11px] text-slate-400 font-medium mr-1">Quick pick:</span>
                                @foreach($subjects as $subj)
                                    <button 
                                        type="button" 
                                        @click="toggle('{{ addslashes($subj->name) }}')"
                                        class="text-[11px] font-medium px-2.5 py-1 rounded-lg border transition cursor-pointer"
                                        :class="selected.includes('{{ addslashes($subj->name) }}') ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100 hover:text-slate-800'"
                                    >
                                        + {{ $subj->name }} ({{ $subj->code }})
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        @error('specialization')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class Assignment (Multi-Select Classrooms from DB) -->
                    <div 
                        x-data="{
                            open: false,
                            selected: {{ json_encode(array_values(array_map('intval', (array) old('classrooms', old('classroom_ids', []))))) }},
                            search: '',
                            items: {{ json_encode($classrooms->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'grade' => $c->grade_level, 'year' => $c->academic_year])) }},
                            toggle(id) {
                                id = parseInt(id);
                                if (this.selected.includes(id)) {
                                    this.selected = this.selected.filter(item => item !== id);
                                } else {
                                    this.selected.push(id);
                                }
                            },
                            get selectedObjects() {
                                return this.items.filter(i => this.selected.includes(i.id));
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
                                Assigned Classes / Rooms
                                <span class="text-xs font-normal text-slate-400 ml-1">(Assign teacher to one or more classrooms)</span>
                            </label>
                            <span 
                                x-show="selected.length > 0" 
                                x-text="selected.length + ' class' + (selected.length > 1 ? 'es' : '') + ' selected'"
                                class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100"
                            ></span>
                        </div>

                        <!-- Hidden Inputs for Form Submission -->
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="classrooms[]" :value="id">
                        </template>

                        <!-- Dropdown Trigger Box -->
                        <div 
                            @click="open = !open" 
                            class="min-h-[44px] w-full px-3 py-2 bg-slate-50 border @error('classrooms') border-rose-300 ring-2 ring-rose-500/10 @else border-slate-200 @enderror rounded-xl cursor-pointer hover:bg-slate-100/60 transition flex items-center justify-between gap-2"
                        >
                            <div class="flex flex-wrap items-center gap-1.5 flex-1">
                                <template x-if="selected.length === 0">
                                    <span class="text-slate-400 font-normal text-xs sm:text-sm">Click to select classes to assign...</span>
                                </template>
                                <template x-for="item in selectedObjects" :key="item.id">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
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
                                    placeholder="Search class or grade level..." 
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
                                                :class="selected.includes(cls.id) ? 'bg-indigo-600 border-indigo-600 text-white' : 'border-slate-300 bg-white'"
                                            >
                                                <svg x-show="selected.includes(cls.id)" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                        @if($classrooms->isNotEmpty())
                            <div class="mt-2 flex flex-wrap items-center gap-1.5">
                                <span class="text-[11px] text-slate-400 font-medium mr-1">Quick assign:</span>
                                @foreach($classrooms as $cls)
                                    <button 
                                        type="button" 
                                        @click="toggle({{ $cls->id }})"
                                        class="text-[11px] font-medium px-2.5 py-1 rounded-lg border transition cursor-pointer"
                                        :class="selected.includes({{ $cls->id }}) ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100 hover:text-slate-800'"
                                    >
                                        + {{ $cls->name }}
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        @error('classrooms')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Office / Residential Address -->
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Office / Residential Address</label>
                        <textarea 
                            name="address" 
                            rows="2" 
                            placeholder="e.g. Building C, Room 204, Phnom Penh" 
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
                        <span>Save Faculty</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
