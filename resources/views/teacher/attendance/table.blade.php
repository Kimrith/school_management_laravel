<table class="w-full text-left border-collapse">
    <thead>
        <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
            <th scope="col" class="py-3.5 pl-6 pr-3">Student</th>
            <th scope="col" class="py-3.5 px-3 text-center">Status</th>
            <th scope="col" class="py-3.5 px-3 pr-6">Remarks / Note</th>
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

                    <!-- Hidden inputs for backend submission -->
                    <input type="hidden" :name="`attendances[${index}][student_id]`" :value="student.id">
                    <input type="hidden" :name="`attendances[${index}][status]`" :value="student.status">
                </td>

                <!-- Interactive Status Selectors -->
                <td class="py-3.5 px-3">
                    <div class="flex items-center justify-center">
                        <div class="inline-flex items-center p-1 rounded-xl bg-slate-100 gap-1">
                            <!-- Present -->
                            <button 
                                type="button" 
                                @click="student.status = 'present'"
                                :class="student.status === 'present' ? 'bg-emerald-600 text-white shadow-xs font-semibold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                                class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer"
                            >
                                Present
                            </button>

                            <!-- Absent -->
                            <button 
                                type="button" 
                                @click="student.status = 'absent'"
                                :class="student.status === 'absent' ? 'bg-rose-600 text-white shadow-xs font-semibold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                                class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer"
                            >
                                Absent
                            </button>

                            <!-- Late -->
                            <button 
                                type="button" 
                                @click="student.status = 'late'"
                                :class="student.status === 'late' ? 'bg-amber-500 text-white shadow-xs font-semibold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                                class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer"
                            >
                                Late
                            </button>

                            <!-- Excused -->
                            <button 
                                type="button" 
                                @click="student.status = 'excused'"
                                :class="student.status === 'excused' ? 'bg-sky-600 text-white shadow-xs font-semibold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                                class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer"
                            >
                                Excused
                            </button>
                        </div>
                    </div>
                </td>

                <!-- Remarks input -->
                <td class="py-3.5 px-3 pr-6">
                    <input 
                        type="text" 
                        :name="`attendances[${index}][remarks]`"
                        x-model="student.remarks"
                        placeholder="Optional remarks (e.g. sick leave, late bus)..."
                        class="w-full max-w-md px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500 text-slate-800"
                    >
                </td>
            </tr>
        </template>

        <template x-if="students.length === 0">
            <tr>
                <td colspan="3" class="py-12 text-center text-slate-400">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                        </div>
                        <p class="font-medium text-slate-700 text-sm">No students enrolled in this classroom</p>
                        <p class="text-xs text-slate-400 mt-1">Please select another classroom or assign students from the administration panel.</p>
                    </div>
                </td>
            </tr>
        </template>
    </tbody>
</table>