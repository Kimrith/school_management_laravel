@extends('layouts.teacher')

@section('title', 'Mark Attendance')
@section('page_title', 'Attendance')

@section('content')
<div class="space-y-6" x-data="{
    selectedClass: '10-A',
    attendanceDate: '{{ date('Y-m-d') }}',
    students: [
        { id: 1, code: 'STU-1001', name: 'Sokha Chan', status: 'present', remarks: '' },
        { id: 2, code: 'STU-1002', name: 'Bopha Vong', status: 'present', remarks: '' },
        { id: 3, code: 'STU-1003', name: 'Dara Rath', status: 'absent', remarks: 'Sick leave requested' },
        { id: 4, code: 'STU-1004', name: 'Chanthou Seng', status: 'present', remarks: '' },
        { id: 5, code: 'STU-1005', name: 'Panha Lim', status: 'late', remarks: 'Traffic delay' },
        { id: 6, code: 'STU-1006', name: 'Monyroth Keo', status: 'present', remarks: '' },
        { id: 7, code: 'STU-1007', name: 'Vireak Chea', status: 'excused', remarks: 'Sports competition' },
    ],
    markAll(status) {
        this.students.forEach(s => s.status = status);
    },
    count(status) {
        return this.students.filter(s => s.status === status).length;
    }
}">
    <!-- Header & Class/Date Filter Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Class Attendance Sheet</h1>
            <p class="text-xs text-slate-500 mt-0.5">Select your assigned classroom and record daily student attendance.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Classroom Dropdown -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Classroom</label>
                <select x-model="selectedClass" class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 font-medium focus:ring-2 focus:ring-emerald-500/20">
                    <option value="10-A">Grade 10-A (Web Dev)</option>
                    <option value="12-A">Grade 12-A (Algorithms)</option>
                </select>
            </div>

            <!-- Date Picker -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Date</label>
                <input type="date" x-model="attendanceDate" class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 font-medium focus:ring-2 focus:ring-emerald-500/20">
            </div>

            <!-- Quick Action: Mark All Present -->
            <div class="self-end">
                <button 
                    type="button" 
                    @click="markAll('present')"
                    class="py-2 px-3.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs sm:text-sm font-semibold transition-colors cursor-pointer"
                >
                    Mark All Present
                </button>
            </div>
        </div>
    </div>

    <!-- Attendance Live Statistics Pills -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <span class="text-xs font-semibold text-emerald-700">Present</span>
            <span class="text-lg font-bold text-emerald-700 font-mono" x-text="count('present')">0</span>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <span class="text-xs font-semibold text-rose-700">Absent</span>
            <span class="text-lg font-bold text-rose-700 font-mono" x-text="count('absent')">0</span>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <span class="text-xs font-semibold text-amber-700">Late</span>
            <span class="text-lg font-bold text-amber-700 font-mono" x-text="count('late')">0</span>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <span class="text-xs font-semibold text-sky-700">Excused</span>
            <span class="text-lg font-bold text-sky-700 font-mono" x-text="count('excused')">0</span>
        </div>
    </div>

    <!-- Attendance Roster Form -->
    <form action="#" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        @csrf
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <th scope="col" class="py-3.5 pl-6 pr-3">Student</th>
                        <th scope="col" class="py-3.5 px-3 text-center">Status</th>
                        <th scope="col" class="py-3.5 px-3">Remarks / Note</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <template x-for="(student, index) in students" :key="student.id">
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <!-- Student info -->
                            <td class="py-3.5 pl-6 pr-3">
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-mono text-slate-400" x-text="'#' + (index + 1)"></span>
                                    <div>
                                        <p class="font-semibold text-slate-900 leading-snug" x-text="student.name"></p>
                                        <p class="text-xs font-mono text-slate-400" x-text="student.code"></p>
                                    </div>
                                </div>
                            </td>

                            <!-- Interactive Status Selectors -->
                            <td class="py-3.5 px-3">
                                <div class="inline-flex items-center justify-center p-1 rounded-xl bg-slate-100 gap-1">
                                    <!-- Present -->
                                    <button 
                                        type="button" 
                                        @click="student.status = 'present'"
                                        :class="student.status === 'present' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                        class="px-3 py-1 rounded-lg text-xs font-semibold transition-all cursor-pointer"
                                    >
                                        Present
                                    </button>

                                    <!-- Absent -->
                                    <button 
                                        type="button" 
                                        @click="student.status = 'absent'"
                                        :class="student.status === 'absent' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                        class="px-3 py-1 rounded-lg text-xs font-semibold transition-all cursor-pointer"
                                    >
                                        Absent
                                    </button>

                                    <!-- Late -->
                                    <button 
                                        type="button" 
                                        @click="student.status = 'late'"
                                        :class="student.status === 'late' ? 'bg-amber-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                        class="px-3 py-1 rounded-lg text-xs font-semibold transition-all cursor-pointer"
                                    >
                                        Late
                                    </button>

                                    <!-- Excused -->
                                    <button 
                                        type="button" 
                                        @click="student.status = 'excused'"
                                        :class="student.status === 'excused' ? 'bg-sky-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                        class="px-3 py-1 rounded-lg text-xs font-semibold transition-all cursor-pointer"
                                    >
                                        Excused
                                    </button>
                                </div>
                            </td>

                            <!-- Remarks input -->
                            <td class="py-3.5 px-3 pr-6">
                                <input 
                                    type="text" 
                                    x-model="student.remarks"
                                    placeholder="Optional remark..."
                                    class="w-full max-w-sm px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500"
                                >
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Submit Footer -->
        <div class="p-4 px-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs text-slate-500">Record will be saved for <span class="font-semibold text-slate-800" x-text="attendanceDate"></span></span>
            <button 
                type="button"
                @click="alert('Attendance recorded successfully!')"
                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-sm transition-all cursor-pointer"
            >
                Submit Attendance
            </button>
        </div>
    </form>
</div>
@endsection
