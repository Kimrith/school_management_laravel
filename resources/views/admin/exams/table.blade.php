@php
    $grouped = isset($groupedExams) ? $groupedExams : $exams->getCollection()->groupBy('title');
@endphp

@forelse($grouped as $title => $titleExams)
    @php
        $firstExam = $titleExams->first();
        $count = $titleExams->count();
        $classroomsList = $titleExams->pluck('classroom.name')->filter()->unique()->implode(', ');
        $minDate = $titleExams->pluck('exam_date')->filter()->min();
        $maxDate = $titleExams->pluck('exam_date')->filter()->max();
        $dateText = $minDate 
            ? ($minDate->eq($maxDate) ? $minDate->format('M d, Y') : $minDate->format('M d') . ' - ' . $maxDate->format('M d, Y'))
            : 'N/A';
    @endphp

    <tbody x-data="{ open: false }" class="border-b border-slate-100 last:border-b-0 text-sm">
        <!-- Master Row (1 row per unique Exam Title) -->
        <tr class="hover:bg-slate-50/70 transition-colors" :class="open ? 'bg-slate-50/50' : ''">
            <!-- Exam Title with Interactive Arrow Toggle -->
            <td class="py-4 pl-6 pr-3">
                <div class="flex items-center gap-2.5">
                    <button 
                        type="button" 
                        @click="open = !open" 
                        class="p-1 rounded-lg hover:bg-slate-200/70 text-slate-500 hover:text-slate-800 transition-colors cursor-pointer"
                        :title="open ? 'Hide subjects' : 'Show subjects'"
                    >
                        <!-- Arrow Right (when collapsed: !open) -->
                        <svg x-show="!open" class="w-4 h-4 text-slate-500 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                        <!-- Arrow Down (when expanded: open) -->
                        <svg x-show="open" x-cloak class="w-4 h-4 text-indigo-600 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <div>
                        <div class="flex items-center gap-2">
                            <span 
                                @click="open = !open" 
                                class="font-bold text-slate-900 leading-snug cursor-pointer hover:text-indigo-600 transition-colors"
                            >
                                {{ $title }}
                            </span>
                            <span 
                                @click="open = !open"
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 cursor-pointer"
                            >
                                {{ $count }} {{ Str::plural('Subject', $count) }}
                            </span>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                            Standard Written Assessment
                        </span>
                    </div>
                </div>
            </td>

            <!-- Subject Column Preview -->
            <td class="py-4 px-3 text-xs text-slate-700 font-medium">
                @if($count === 1)
                    <span class="font-semibold text-slate-800">{{ $firstExam->subject->name ?? 'N/A' }}</span>
                    <span class="text-slate-400 font-mono text-[11px]">({{ $firstExam->subject->code ?? '' }})</span>
                @else
                    <button 
                        type="button" 
                        @click="open = !open" 
                        class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-800 font-semibold cursor-pointer"
                    >
                        <span>View {{ $count }} subjects</span>
                        <svg class="w-3.5 h-3.5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                @endif
            </td>

            <!-- Classroom Column -->
            <td class="py-4 px-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-medium">
                    {{ $classroomsList ?: 'N/A' }}
                </span>
            </td>

            <!-- Exam Date Column -->
            <td class="py-4 px-3 font-mono text-xs text-slate-600">
                {{ $dateText }}
            </td>

            <!-- Total Marks Column -->
            <td class="py-4 px-3 font-mono font-semibold text-slate-900 text-xs">
                {{ number_format($firstExam->total_marks, 2) }} Marks
            </td>

            <!-- Actions Column -->
            <td class="py-4 pl-3 pr-6 text-right">
                <div class="inline-flex items-center gap-2">
                    <button 
                        type="button" 
                        @click="openAssignModal('{{ addslashes($title) }}', '{{ $firstExam->classroom_id }}')" 
                        class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition-colors cursor-pointer"
                        title="Assign another subject under '{{ $title }}'"
                    >
                        + Add Subject
                    </button>

                    @if($count === 1)
                        <form 
                            action="{{ route('admin.exams.destroy', $firstExam) }}" 
                            method="POST" 
                            onsubmit="return confirm('Remove {{ addslashes($firstExam->subject->name ?? 'subject') }} from {{ addslashes($title) }}?');" 
                            class="inline ml-1"
                        >
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="p-1 text-slate-400 hover:text-rose-600 transition-colors cursor-pointer" 
                                title="Remove this subject"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <button 
                            type="button" 
                            @click="open = !open" 
                            class="text-xs font-semibold text-slate-600 hover:text-slate-900 cursor-pointer"
                        >
                            <span x-text="open ? 'Close' : 'Details'"></span>
                        </button>
                    @endif
                </div>
            </td>
        </tr>

        <!-- Expanded Sub-Table: Shows all assigned subjects under this exam title -->
        <tr x-show="open" x-cloak class="bg-slate-50/60 border-t border-slate-100">
            <td colspan="6" class="p-0">
                <div class="px-6 py-3.5 space-y-2.5 bg-slate-50/80 border-y border-slate-200/70">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            Assigned Subjects for <span class="text-indigo-600 font-extrabold">"{{ $title }}"</span> ({{ $count }})
                        </p>
                        <button 
                            type="button" 
                            @click="openAssignModal('{{ addslashes($title) }}', '{{ $firstExam->classroom_id }}')" 
                            class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-600 hover:text-indigo-800"
                        >
                            <span>+ Assign Another Subject</span>
                        </button>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden shadow-2xs">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-100/75 text-[10px] font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-200/70">
                                    <th class="py-2.5 px-4">Subject Name</th>
                                    <th class="py-2.5 px-3">Subject Code</th>
                                    <th class="py-2.5 px-3">Classroom</th>
                                    <th class="py-2.5 px-3">Exam Date</th>
                                    <th class="py-2.5 px-3">Total Marks</th>
                                    <th class="py-2.5 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($titleExams as $item)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-2.5 px-4 font-semibold text-slate-800">
                                            {{ $item->subject->name ?? 'N/A' }}
                                        </td>
                                        <td class="py-2.5 px-3 font-mono text-slate-500 text-[11px]">
                                            {{ $item->subject->code ?? 'N/A' }}
                                        </td>
                                        <td class="py-2.5 px-3">
                                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-medium">
                                                {{ $item->classroom->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="py-2.5 px-3 font-mono text-slate-600">
                                            {{ $item->exam_date ? $item->exam_date->format('M d, Y') : 'N/A' }}
                                        </td>
                                        <td class="py-2.5 px-3 font-mono font-semibold text-slate-900">
                                            {{ number_format($item->total_marks, 2) }} Marks
                                        </td>
                                        <td class="py-2.5 px-4 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <form 
                                                    action="{{ route('admin.exams.destroy', $item) }}" 
                                                    method="POST" 
                                                    onsubmit="return confirm('Remove {{ addslashes($item->subject->name ?? 'subject') }} from {{ addslashes($title) }}?');" 
                                                    class="inline ml-1"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        type="submit" 
                                                        class="p-1 text-slate-400 hover:text-rose-600 transition-colors cursor-pointer" 
                                                        title="Remove this subject paper"
                                                    >
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
@empty
    <tbody class="divide-y divide-slate-100 text-sm">
        <tr>
            <td colspan="6" class="py-12 text-center text-xs text-slate-500">
                @if(request()->filled('search') || request()->filled('classroom_id'))
                    <div class="space-y-1">
                        <p class="font-medium text-slate-700">No exams match your search criteria</p>
                        <p class="text-slate-400">Try adjusting your search terms or clearing filters.</p>
                    </div>
                @else
                    No exams scheduled yet. Click "Schedule Exam" to add one.
                @endif
            </td>
        </tr>
    </tbody>
@endforelse