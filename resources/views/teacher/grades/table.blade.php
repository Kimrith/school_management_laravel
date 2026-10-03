<form method="POST" action="{{ route('teacher.grades.store') }}" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
    @csrf
    <input type="hidden" name="exam_id" :value="selectedExam">
    <input type="hidden" name="classroom_id" :value="selectedClass">

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                    <th scope="col" class="py-3.5 pl-6 pr-3">Student Name</th>
                    <th scope="col" class="py-3.5 px-3">Student Code</th>
                    <th scope="col" class="py-3.5 px-3 w-44">Score (Max 100)</th>
                    <th scope="col" class="py-3.5 px-3 text-center">Calculated Grade</th>
                    <th scope="col" class="py-3.5 pr-6 text-right">Remarks</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                <template x-for="(student, idx) in students" :key="student.id">
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-3.5 pl-6 pr-3 font-semibold text-slate-900">
                            <span x-text="student.name"></span>
                            <input type="hidden" :name="'marks[' + idx + '][student_id]'" :value="student.id">
                        </td>
                        <td class="py-3.5 px-3 font-mono text-xs text-indigo-600" x-text="student.code"></td>
                        <td class="py-3.5 px-3">
                            <div class="flex items-center gap-2">
                                <input 
                                    type="number" 
                                    step="0.5" 
                                    min="0" 
                                    max="100"
                                    :name="'marks[' + idx + '][marks_obtained]'"
                                    x-model="student.marks" 
                                    placeholder="0 - 100"
                                    class="w-28 px-3 py-1.5 text-sm font-mono font-semibold bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                >
                                <span class="text-xs text-slate-400">/ 100</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-3 text-center">
                            <span 
                                class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-bold font-mono border"
                                :class="gradeColor(student.marks)"
                                x-text="calculateGrade(student.marks)"
                            ></span>
                        </td>
                        <td class="py-3.5 pr-6 text-right text-xs">
                            <span x-show="student.marks !== null && student.marks !== '' && student.marks >= 50" class="text-emerald-600 font-medium">Passed</span>
                            <span x-show="student.marks !== null && student.marks !== '' && student.marks < 50" class="text-rose-600 font-medium">Needs Attention</span>
                            <span x-show="student.marks === null || student.marks === ''" class="text-slate-400">Not graded</span>
                        </td>
                    </tr>
                </template>
                <tr x-show="!students || students.length === 0">
                    <td colspan="5" class="py-12 text-center text-slate-400 text-sm">
                        <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        No enrolled students found for the selected classroom.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="p-4 px-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
        <span class="text-xs text-slate-500">Changes will save to the database and reflect on student report cards immediately.</span>
        <button 
            type="submit" 
            :disabled="!students || students.length === 0 || !selectedExam"
            class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold text-xs sm:text-sm rounded-xl shadow-xs transition-all cursor-pointer"
        >
            Publish Marks
        </button>
    </div>
</form>