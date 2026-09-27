@extends('layouts.admin')

@section('title', 'Classrooms & Grades')
@section('page_title', 'Classes')

@section('content')
<div class="space-y-6" x-data="{ addClassModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Classrooms & Grades</h1>
            <p class="text-sm text-slate-500 mt-1">Manage active grade levels, room allocations, and student capacities.</p>
        </div>
        <div class="flex items-center gap-3">
            <button 
                @click="addClassModal = true"
                type="button" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm hover:shadow-md transition-all duration-150 cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Classroom</span>
            </button>
        </div>
    </div>

    <!-- Active Classrooms Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @php
            $sampleClassrooms = [
                ['name' => 'Grade 10-A', 'level' => 'Grade 10', 'year' => '2025-2026', 'students' => 38, 'capacity' => 40, 'room' => 'Room 301', 'teacher' => 'Prof. Virak Meas', 'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200'],
                ['name' => 'Grade 11-B', 'level' => 'Grade 11', 'year' => '2025-2026', 'students' => 35, 'capacity' => 40, 'room' => 'Room 204', 'teacher' => 'Dr. Sopheap Ouk', 'badge' => 'bg-sky-50 text-sky-700 border-sky-200'],
                ['name' => 'Grade 12-A', 'level' => 'Grade 12', 'year' => '2025-2026', 'students' => 40, 'capacity' => 40, 'room' => 'Room 402', 'teacher' => 'Mr. Samnang Heng', 'badge' => 'bg-purple-50 text-purple-700 border-purple-200'],
                ['name' => 'Grade 9-C', 'level' => 'Grade 9', 'year' => '2025-2026', 'students' => 32, 'capacity' => 35, 'room' => 'Room 105', 'teacher' => 'Ms. Kunthea Chea', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['name' => 'Grade 10-B', 'level' => 'Grade 10', 'year' => '2025-2026', 'students' => 36, 'capacity' => 40, 'room' => 'Room 303', 'teacher' => 'Prof. Virak Meas', 'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200'],
                ['name' => 'Grade 11-A', 'level' => 'Grade 11', 'year' => '2025-2026', 'students' => 39, 'capacity' => 40, 'room' => 'Room 202', 'teacher' => 'Dr. Sopheap Ouk', 'badge' => 'bg-sky-50 text-sky-700 border-sky-200'],
            ];
        @endphp

        @foreach($sampleClassrooms as $class)
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:border-slate-300 hover:shadow-sm transition-all duration-200 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $class['badge'] }}">
                            {{ $class['level'] }}
                        </span>
                        <span class="text-xs font-mono text-slate-400 font-medium">{{ $class['room'] }}</span>
                    </div>

                    <h2 class="text-lg font-bold text-slate-900 mt-3">{{ $class['name'] }}</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Head Teacher: <span class="text-slate-700 font-medium">{{ $class['teacher'] }}</span></p>

                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-1.5">
                            <span>Enrollment: <strong class="text-slate-800">{{ $class['students'] }}</strong> / {{ $class['capacity'] }}</span>
                            <span class="font-mono font-semibold text-slate-700">{{ round(($class['students'] / $class['capacity']) * 100) }}%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div 
                                class="h-full rounded-full {{ $class['students'] >= $class['capacity'] ? 'bg-amber-500' : 'bg-indigo-600' }}" 
                                style="width: {{ ($class['students'] / $class['capacity']) * 100 }}%"
                            ></div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-mono">{{ $class['year'] }}</span>
                    <div class="flex items-center gap-2">
                        <a href="{{ url('/admin/students') }}" class="font-semibold text-indigo-600 hover:text-indigo-800">Students &rarr;</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add Classroom Modal -->
    <div x-show="addClassModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            <div x-show="addClassModal" @click="addClassModal = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs"></div>
            <div x-show="addClassModal" class="relative bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-xl border border-slate-200/80 my-8 z-10">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Create New Classroom</h3>
                    <button @click="addClassModal = false" class="p-1 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="#" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Classroom Name</label>
                        <input type="text" placeholder="e.g. Grade 10-C" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Grade Level</label>
                        <select class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                            <option>Grade 9</option>
                            <option>Grade 10</option>
                            <option>Grade 11</option>
                            <option>Grade 12</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Academic Year</label>
                        <input type="text" value="2025-2026" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none font-mono">
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="addClassModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 rounded-xl border border-slate-200">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm">Save Classroom</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
