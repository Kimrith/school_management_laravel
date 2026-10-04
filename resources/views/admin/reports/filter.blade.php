<!-- Filter Toolbar -->
<div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/50">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-center gap-3">
        <!-- Search Input -->
        <div class="relative flex-1 min-w-[200px]">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Search student name, code, exam or subject..."
                class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
            >
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
        </div>

        <!-- Classroom Filter -->
        <div>
            <select 
                name="classroom_id" 
                onchange="this.form.submit()"
                class="py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 font-medium focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
            >
                <option value="all">All Classrooms</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Exam Title Filter -->
        <div>
            <select 
                name="exam_title" 
                onchange="this.form.submit()"
                class="py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 font-medium focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
            >
                <option value="all">All Examinations</option>
                @foreach($examTitles as $title)
                    <option value="{{ $title }}" {{ request('exam_title') == $title ? 'selected' : '' }}>
                        {{ $title }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Subject Filter -->
        <div>
            <select 
                name="subject_id" 
                onchange="this.form.submit()"
                class="py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 font-medium focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
            >
                <option value="all">All Subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }} ({{ $subject->code }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Grade / Status Filter -->
        <div>
            <select 
                name="status" 
                onchange="this.form.submit()"
                class="py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 font-medium focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
            >
                <option value="">All Grades & Status</option>
                <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }}>Passed (&ge; 50)</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Needs Attention (&lt; 50)</option>
                <option value="A" {{ request('status') === 'A' ? 'selected' : '' }}>Grade A (90-100)</option>
                <option value="B+" {{ request('status') === 'B+' ? 'selected' : '' }}>Grade B+ (85-89)</option>
                <option value="B" {{ request('status') === 'B' ? 'selected' : '' }}>Grade B (80-84)</option>
                <option value="C+" {{ request('status') === 'C+' ? 'selected' : '' }}>Grade C+ (70-79)</option>
                <option value="C" {{ request('status') === 'C' ? 'selected' : '' }}>Grade C (65-69)</option>
                <option value="D" {{ request('status') === 'D' ? 'selected' : '' }}>Grade D (50-64)</option>
                <option value="F" {{ request('status') === 'F' ? 'selected' : '' }}>Grade F (&lt; 50)</option>
            </select>
        </div>

        <button 
            type="submit" 
            class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-xs font-semibold text-white transition-colors cursor-pointer"
        >
            Filter
        </button>

        @if(request()->anyFilled(['search', 'classroom_id', 'exam_title', 'subject_id', 'status']))
            <a 
                href="{{ route('admin.reports.index') }}" 
                class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline px-2"
            >
                Clear Filters
            </a>
        @endif
    </form>
</div>
