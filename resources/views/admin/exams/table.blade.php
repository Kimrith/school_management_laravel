<tbody class="divide-y divide-slate-100 text-sm">
    @forelse($exams as $exam)
        <tr class="hover:bg-slate-50/60 transition-colors">
            <td class="py-4 pl-6 pr-3">
                <p class="font-semibold text-slate-900 leading-snug">{{ $exam->title }}</p>
                <span class="inline-flex items-center gap-1.5 text-xs text-indigo-600 font-medium">Standard Written Exam</span>
            </td>
            <td class="py-4 px-3 text-xs text-slate-700 font-medium">
                {{ $exam->subject->name ?? 'N/A' }} 
                <span class="text-slate-400">({{ $exam->subject->code ?? '' }})</span>
            </td>
            <td class="py-4 px-3">
                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-medium">
                    {{ $exam->classroom->name ?? 'N/A' }}
                </span>
            </td>
            <td class="py-4 px-3 font-mono text-xs text-slate-600">
                {{ $exam->exam_date ? $exam->exam_date->format('M d, Y') : 'N/A' }}
            </td>
            <td class="py-4 px-3 font-mono font-semibold text-slate-900 text-xs">
                {{ number_format($exam->total_marks, 2) }} Marks
            </td>
            <td class="py-4 pl-3 pr-6 text-right">
                <div class="inline-flex items-center gap-2">
                    <a 
                        href="{{ url('/teacher/grades') }}" 
                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline"
                    >
                        Marks Sheet &rarr;
                    </a>
                </div>
            </td>
        </tr>
    @empty
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
    @endforelse
</tbody>