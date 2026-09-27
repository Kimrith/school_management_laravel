@extends('layouts.teacher')

@section('title', 'Exam Grades & Marks Entry')
@section('page_title', 'Grades')

@section('content')
<div class="space-y-6" x-data="{
    selectedExam: '1',
    selectedClass: '10-A',
    students: [
        { id: 1, code: 'STU-1001', name: 'Sokha Chan', marks: 95.5 },
        { id: 2, code: 'STU-1002', name: 'Bopha Vong', marks: 88.0 },
        { id: 3, code: 'STU-1003', name: 'Dara Rath', marks: 74.5 },
        { id: 4, code: 'STU-1004', name: 'Chanthou Seng', marks: 91.0 },
        { id: 5, code: 'STU-1005', name: 'Panha Lim', marks: 62.0 },
        { id: 6, code: 'STU-1006', name: 'Monyroth Keo', marks: 82.5 },
    ],
    calculateGrade(score) {
        let s = parseFloat(score);
        if (isNaN(s)) return '-';
        if (s >= 90) return 'A';
        if (s >= 85) return 'B+';
        if (s >= 80) return 'B';
        if (s >= 70) return 'C+';
        if (s >= 65) return 'C';
        if (s >= 50) return 'D';
        return 'F';
    },
    gradeColor(score) {
        let g = this.calculateGrade(score);
        if (g === 'A') return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        if (g === 'B+' || g === 'B') return 'bg-sky-50 text-sky-700 border-sky-200';
        if (g === 'C+' || g === 'C') return 'bg-indigo-50 text-indigo-700 border-indigo-200';
        if (g === 'D') return 'bg-amber-50 text-amber-700 border-amber-200';
        return 'bg-rose-50 text-rose-700 border-rose-200';
    },
    classAverage() {
        let total = this.students.reduce((acc, s) => acc + (parseFloat(s.marks) || 0), 0);
        return (total / this.students.length).toFixed(1);
    }
}">
    <!-- Header Toolbar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Input Exam Marks</h1>
            <p class="text-xs text-slate-500 mt-0.5">Record marks obtained for scheduled examinations. Letter grades calculate automatically.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Exam Picker -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Select Exam</label>
                <select x-model="selectedExam" class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 font-medium">
                    <option value="1">Midterm Exam (Web Dev)</option>
                    <option value="2">Final Term Exam (Algorithms)</option>
                </select>
            </div>

            <!-- Classroom Picker -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Classroom</label>
                <select x-model="selectedClass" class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 font-medium">
                    <option value="10-A">Grade 10-A</option>
                    <option value="12-A">Grade 12-A</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400">Class Average</p>
                <p class="text-2xl font-bold text-slate-900 mt-1 font-mono"><span x-text="classAverage()"></span> / 100</p>
            </div>
            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">Passing</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400">Total Enrolled</p>
                <p class="text-2xl font-bold text-slate-900 mt-1 font-mono" x-text="students.length"></p>
            </div>
            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">All Graded</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400">Grading Scale</p>
                <p class="text-xs text-slate-500 mt-1">A: 90-100 &bull; B: 80-89 &bull; C: 65-79</p>
            </div>
            <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">Standard 4.0</span>
        </div>
    </div>

    <!-- Marks Entry Table Form -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
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
                            <td class="py-3.5 pl-6 pr-3 font-semibold text-slate-900" x-text="student.name"></td>
                            <td class="py-3.5 px-3 font-mono text-xs text-indigo-600" x-text="student.code"></td>
                            <td class="py-3.5 px-3">
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="number" 
                                        step="0.5" 
                                        min="0" 
                                        max="100"
                                        x-model="student.marks" 
                                        class="w-24 px-3 py-1.5 text-sm font-mono font-semibold bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
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
                            <td class="py-3.5 pr-6 text-right text-xs text-slate-400">
                                <span x-show="student.marks >= 50" class="text-emerald-600 font-medium">Passed</span>
                                <span x-show="student.marks < 50" class="text-rose-600 font-medium">Needs Attention</span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="p-4 px-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs text-slate-400">Changes will be visible in student report cards immediately.</span>
            <button 
                type="button" 
                @click="alert('All grades saved and published successfully!')"
                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-sm transition-all cursor-pointer"
            >
                Publish Marks
            </button>
        </div>
    </div>
</div>
@endsection
