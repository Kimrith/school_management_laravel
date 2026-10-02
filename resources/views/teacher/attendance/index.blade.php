@extends('layouts.teacher')

@section('title', 'Mark Attendance')
@section('page_title', 'Attendance')

@section('content')
<div class="space-y-6" x-data="{
    selectedClassId: '{{ $selectedClassroomId }}',
    attendanceDate: '{{ $date }}',
    students: {{ Js::from($studentsPayload) }},
    markAll(status) {
        this.students.forEach(s => s.status = status);
    },
    count(status) {
        return this.students.filter(s => s.status === status).length;
    },
    onFilterChange() {
        const url = new URL(window.location.href);
        if (this.selectedClassId) {
            url.searchParams.set('classroom_id', this.selectedClassId);
        } else {
            url.searchParams.delete('classroom_id');
        }
        url.searchParams.set('date', this.attendanceDate);
        window.location.href = url.toString();
    }
}">
    {{-- Success Notification --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-emerald-800 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-100 rounded-xl text-emerald-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-sm">{{ session('success') }}</p>
                    <p class="text-xs text-emerald-600 mt-0.5">The attendance records have been synchronized to the school-wide database.</p>
                </div>
            </div>
            <a 
                href="{{ route('admin.attendances.index', ['date' => $date, 'classroom_id' => $selectedClassroomId]) }}" 
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-xs transition-colors shrink-0"
            >
                <span>View in Admin Portal</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-sm shadow-xs">
            <p class="font-semibold mb-1">Please correct the following errors:</p>
            <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- No Assigned Classrooms Warning --}}
    @if($classrooms->isEmpty())
        <div class="p-5 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-4 text-amber-900 shadow-xs">
            <div class="p-2 bg-amber-100 rounded-xl text-amber-700 shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-sm text-amber-900">No Assigned Classrooms</h3>
                <p class="text-xs text-amber-700 mt-1 leading-relaxed">
                    You currently have no classrooms assigned to your teacher account. Attendance recording is restricted to assigned classes only. Please contact your school administrator to assign classes to your account.
                </p>
            </div>
        </div>
    @endif

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
                <select 
                    x-model="selectedClassId" 
                    @change="onFilterChange()"
                    :disabled="{{ $classrooms->isEmpty() ? 'true' : 'false' }}"
                    class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 font-medium focus:ring-2 focus:ring-emerald-500/20 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    @forelse($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ $selectedClassroomId == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }} ({{ $classroom->studentProfiles->count() }} students)
                        </option>
                    @empty
                        <option value="">No assigned classrooms</option>
                    @endforelse
                </select>
            </div>

            <!-- Date Picker -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Date</label>
                <input 
                    type="date" 
                    x-model="attendanceDate" 
                    @change="onFilterChange()"
                    class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 font-medium focus:ring-2 focus:ring-emerald-500/20 cursor-pointer"
                >
            </div>

            <!-- Quick Action: Mark All Present -->
            <div class="self-end">
                <button 
                    type="button" 
                    @click="markAll('present')"
                    :disabled="students.length === 0"
                    class="py-2 px-3.5 bg-emerald-50 hover:bg-emerald-100 disabled:opacity-50 disabled:cursor-not-allowed text-emerald-700 border border-emerald-200 rounded-xl text-xs sm:text-sm font-semibold transition-colors cursor-pointer"
                >
                    Mark All Present
                </button>
            </div>
        </div>
    </div>

    <!-- Attendance Live Statistics Pills -->
    @include('teacher.attendance.statistics-cart')

    <!-- Attendance Roster Form -->
    <form action="{{ route('teacher.attendance.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        @csrf
        <input type="hidden" name="classroom_id" :value="selectedClassId">
        <input type="hidden" name="date" :value="attendanceDate">

        <div class="overflow-x-auto">
            @include('teacher.attendance.table')
        </div>

        <!-- Submit Footer -->
        <div class="p-4 px-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <span class="text-xs text-slate-500">
                Attendance sheet for <span class="font-semibold text-slate-800">{{ $selectedClassroom?->name ?? 'Classroom' }}</span> on <span class="font-semibold text-slate-800" x-text="attendanceDate"></span>
            </span>
            <div class="flex items-center gap-3">
                <button 
                    type="submit"
                    :disabled="students.length === 0"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold text-xs sm:text-sm rounded-xl shadow-sm transition-all cursor-pointer inline-flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    <span>Submit Attendance</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
