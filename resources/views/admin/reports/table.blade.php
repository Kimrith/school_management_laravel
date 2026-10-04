<tbody class="divide-y divide-slate-100 text-sm">
    @forelse($marks as $record)
        @php
            $score = (float) $record->marks_obtained;
            $grade = $record->grade_letter ?? '-';
            $gradeColor = match($grade) {
                'A' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'B+', 'B' => 'bg-sky-50 text-sky-700 border-sky-200',
                'C+', 'C' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'D' => 'bg-amber-50 text-amber-700 border-amber-200',
                default => 'bg-rose-50 text-rose-700 border-rose-200',
            };
            $studentName = $record->student?->user?->name ?? 'Student #'.$record->student_id;
            $studentCode = $record->student?->student_code ?? 'STU-'.$record->student_id;
            $classroomName = $record->student?->classroom?->name ?? $record->exam?->classroom?->name ?? 'Classroom';
            $examTitle = $record->exam?->title ?? 'Exam';
            $subjectName = $record->exam?->subject?->name ?? 'Subject';
            $subjectCode = $record->exam?->subject?->code ?? '';
            $studentAvatar = $record->student?->avatar_url;
        @endphp
        <tr class="hover:bg-slate-50/70 transition-colors">
            <!-- Student Info -->
            <td class="py-3.5 pl-6 pr-3">
                <div class="flex items-center gap-3">
                    @if($studentAvatar)
                        <img src="{{ $studentAvatar }}" alt="{{ $studentName }}" class="w-8 h-8 rounded-full object-cover shrink-0 border border-slate-200 shadow-2xs">
                    @else
                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs uppercase shrink-0">
                            {{ strtoupper(substr($studentName, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold text-slate-900 leading-snug">{{ $studentName }}</p>
                        <span class="font-mono text-[11px] text-indigo-600 font-semibold">{{ $studentCode }}</span>
                    </div>
                </div>
            </td>

            <!-- Classroom -->
            <td class="py-3.5 px-3">
                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-medium">
                    {{ $classroomName }}
                </span>
            </td>

            <!-- Examination & Subject -->
            <td class="py-3.5 px-3">
                <p class="font-bold text-slate-900 leading-snug text-xs">{{ $examTitle }}</p>
                <p class="text-xs text-slate-600 mt-0.5">
                    {{ $subjectName }}
                    @if($subjectCode)
                        <span class="text-slate-400 font-mono text-[11px]">({{ $subjectCode }})</span>
                    @endif
                </p>
            </td>

            <!-- Exam Date -->
            <td class="py-3.5 px-3 font-mono text-xs text-slate-600">
                {{ $record->exam?->exam_date ? $record->exam->exam_date->format('M d, Y') : ($record->created_at ? $record->created_at->format('M d, Y') : 'N/A') }}
            </td>

            <!-- Score Obtained -->
            <td class="py-3.5 px-3">
                <div class="flex items-center gap-2">
                    <span class="font-mono font-bold text-slate-900 text-sm">
                        {{ number_format($score, 2) }}
                    </span>
                    <span class="text-[11px] text-slate-400">/ 100</span>
                </div>
                <div class="w-24 bg-slate-100 rounded-full h-1.5 mt-1 overflow-hidden">
                    <div 
                        class="h-1.5 rounded-full {{ $score >= 50 ? 'bg-emerald-500' : 'bg-rose-500' }}" 
                        style="width: {{ min(100, max(0, $score)) }}%"
                    ></div>
                </div>
            </td>

            <!-- Letter Grade -->
            <td class="py-3.5 px-3 text-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold font-mono border {{ $gradeColor }}">
                    {{ $grade }}
                </span>
            </td>

            <!-- Status -->
            <td class="py-3.5 px-3 text-xs">
                @if($score >= 50)
                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Passed
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 font-semibold text-rose-600">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        Needs Attention
                    </span>
                @endif
            </td>

            <!-- Actions -->
            <td class="py-3.5 pl-3 pr-6 text-right">
                <div class="inline-flex items-center gap-3">
                    <a 
                        href="{{ route('pdf.report-card', ['student_id' => $record->student_id, 'user_id' => $record->student?->user_id]) }}" 
                        target="_blank"
                        class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline"
                        title="View Student Report Card"
                    >
                        <span>Report Card</span>
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="py-16 text-center">
                <div class="max-w-sm mx-auto space-y-2">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl">
                        📋
                    </div>
                    <p class="font-bold text-slate-800 text-sm">No Student Marks Found</p>
                    <p class="text-xs text-slate-500">
                        @if(request()->anyFilled(['search', 'classroom_id', 'exam_title', 'subject_id', 'status']))
                            No records match your selected filter criteria. Try adjusting your filters.
                        @else
                            Teachers have not published any examination marks yet. Once marks are submitted via the Teacher Portal, they will appear here automatically.
                        @endif
                    </p>
                    @if(request()->anyFilled(['search', 'classroom_id', 'exam_title', 'subject_id', 'status']))
                        <div class="pt-2">
                            <a href="{{ route('admin.reports.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                                Clear all filters &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
