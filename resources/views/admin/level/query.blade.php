@if($levels->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($levels as $lvl)
            @php
                $isActive = strtolower($lvl->status) === 'active';
                $classroomsCount = $lvl->classrooms_count ?? $lvl->classrooms()->count();
            @endphp

            <div 
                class="bg-white rounded-3xl p-6 border transition-all duration-200 flex flex-col justify-between group shadow-xs hover:shadow-md {{ $isActive ? 'border-slate-200/80 hover:border-slate-300' : 'border-amber-200/90 bg-amber-50/20' }}"
            >
                <div>
                    <!-- Card Top Header: Status & Icon -->
                    <div class="flex items-center justify-between gap-2">
                        <div class="w-10 h-10 rounded-2xl {{ $isActive ? 'bg-indigo-50 border border-indigo-100 text-indigo-600' : 'bg-amber-50 border border-amber-100 text-amber-600' }} flex items-center justify-center font-bold text-base shadow-2xs">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                            </svg>
                        </div>

                        <div>
                            @if($isActive)
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-amber-700 bg-amber-100/80 px-2.5 py-0.5 rounded-full border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Suspended
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Level Title -->
                    <div class="mt-4">
                        <h2 class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition-colors tracking-tight">
                            {{ $lvl->name }}
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            Academic Tier &bull; Level ID: <span class="font-mono text-slate-600 font-semibold">#LVL-{{ str_pad($lvl->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </p>
                    </div>

                    <!-- Associated Classrooms Info -->
                    <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Assigned Classrooms:</span>
                        <div class="flex items-center gap-1.5 font-semibold text-slate-800">
                            @if($classroomsCount > 0)
                                <a 
                                    href="{{ route('admin.classes.index', ['grade' => $lvl->name]) }}" 
                                    class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100 transition-colors"
                                >
                                    <span>{{ $classroomsCount }} {{ $classroomsCount > 1 ? 'Sections' : 'Section' }}</span>
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </a>
                            @else
                                <span class="text-slate-400 italic font-normal">None linked</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card Bottom Actions Footer -->
                <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-[11px] text-slate-400 font-mono">
                        {{ $lvl->created_at ? $lvl->created_at->format('M d, Y') : 'System Registered' }}
                    </span>

                    <div class="flex items-center gap-1">
                        <!-- Quick Modal Edit Button -->
                        <button 
                            type="button" 
                            @click="selectedLevel = {
                                id: '{{ $lvl->id }}',
                                name: '{{ addslashes($lvl->name) }}',
                                status: '{{ addslashes($lvl->status) }}'
                            }; editModalOpen = true;"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors cursor-pointer"
                            title="Edit Level"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </button>

                        <!-- Full Page Editor Link -->
                        <a 
                            href="{{ route('admin.levels.edit', $lvl) }}" 
                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                            title="Full Page Editor"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                        </a>

                        <!-- Suspend / Reinstate Toggle Form -->
                        <form action="{{ route('admin.levels.toggle-status', $lvl) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button 
                                type="submit" 
                                class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors cursor-pointer"
                                title="{{ $isActive ? 'Suspend Level' : 'Reinstate Level' }}"
                            >
                                @if($isActive)
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                @endif
                            </button>
                        </form>

                        <!-- Delete Button -->
                        <button 
                            type="button" 
                            @click="selectedLevel = {
                                id: '{{ $lvl->id }}',
                                name: '{{ addslashes($lvl->name) }}'
                            }; deleteModalOpen = true;"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                            title="Delete Level"
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

    @if($levels->hasPages())
        <div class="mt-6">
            {{ $levels->links() }}
        </div>
    @endif
@else
    <!-- Clean Empty State -->
    <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-xs max-w-lg mx-auto">
        <div class="w-16 h-16 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-900">No Academic Levels Found</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">
            @if(request('search') || request('status'))
                No academic levels match your current search query or active filter.
            @else
                Set up educational tiers and grade classifications to organize curriculum and classrooms.
            @endif
        </p>
        <div class="mt-5 flex items-center justify-center gap-3">
            @if(request('search') || request('status'))
                <a 
                    href="{{ route('admin.levels.index') }}" 
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors"
                >
                    Clear Filter
                </a>
            @endif
            <button 
                type="button" 
                @click="addModalOpen = true"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs hover:shadow transition-all cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Level</span>
            </button>
        </div>
    </div>
@endif
