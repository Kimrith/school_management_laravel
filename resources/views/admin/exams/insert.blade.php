<!-- Schedule Exam Modal -->
<div x-show="addExamModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <div x-show="addExamModal" @click="addExamModal = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs"></div>
        <div x-show="addExamModal" class="relative bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-xl border border-slate-200/80 my-8 z-10">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">Schedule Examination</h3>
                <button @click="addExamModal = false" class="p-1 text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('admin.exams.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Exam Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Midterm Examination" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Subject</label>
                    <select name="subject_id" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none" required>
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} ({{ $subject->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Classroom</label>
                    <select name="classroom_id" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none" required>
                        <option value="">Select Classroom</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Exam Date</label>
                        <input type="date" name="exam_date" value="{{ old('exam_date') }}" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Total Marks</label>
                        <input type="number" step="0.01" name="total_marks" value="{{ old('total_marks', '100.00') }}" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none font-mono" required>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="addExamModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 rounded-xl border border-slate-200">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm">Schedule Exam</button>
                </div>
            </form>
        </div>
    </div>
</div>