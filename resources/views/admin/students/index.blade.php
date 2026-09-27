@extends('layouts.admin')

@section('title', 'Students Management')
@section('page_title', 'Students')

@section('content')
<div class="space-y-6" x-data="{ addModalOpen: false, deleteModalOpen: false, selectedStudent: null }">
    <!-- Header with Breadcrumbs & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Students Directory</h1>
            <p class="text-sm text-slate-500 mt-1">Manage student profiles, enrollments, and academic placements.</p>
        </div>
        <div class="flex items-center gap-3">
            <button 
                @click="addModalOpen = true"
                type="button" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm hover:shadow-md transition-all duration-150 cursor-pointer"
            >
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Student</span>
            </button>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <!-- Search bar -->
            <div class="relative w-full sm:w-72">
                <input 
                    type="text" 
                    placeholder="Search by code, name, phone..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                >
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>

            <!-- Class Filter -->
            <select class="w-full sm:w-44 py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                <option value="">All Classrooms</option>
                <option value="10-A">Grade 10-A</option>
                <option value="11-B">Grade 11-B</option>
                <option value="12-A">Grade 12-A</option>
            </select>
        </div>

        <div class="flex items-center gap-2 text-xs text-slate-500 self-end md:self-auto">
            <span>Showing <span class="font-semibold text-slate-800">5</span> of <span class="font-semibold text-slate-800">1,284</span> students</span>
        </div>
    </div>

    <!-- Students Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <th scope="col" class="py-3.5 pl-6 pr-3">Student Code</th>
                        <th scope="col" class="py-3.5 px-3">Student Name</th>
                        <th scope="col" class="py-3.5 px-3">Classroom</th>
                        <th scope="col" class="py-3.5 px-3">Gender</th>
                        <th scope="col" class="py-3.5 px-3">Parent Contact</th>
                        <th scope="col" class="py-3.5 px-3">Status</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @php
                        $studentsList = [
                            ['code' => 'STU-1001', 'name' => 'Sokha Chan', 'email' => 'sokha@example.com', 'class' => 'Grade 10-A', 'gender' => 'male', 'parent' => 'Chan Dara', 'phone' => '012 345 678', 'status' => 'active'],
                            ['code' => 'STU-1002', 'name' => 'Bopha Vong', 'email' => 'bopha@example.com', 'class' => 'Grade 10-A', 'gender' => 'female', 'parent' => 'Vong Meas', 'phone' => '015 889 221', 'status' => 'active'],
                            ['code' => 'STU-1003', 'name' => 'Dara Rath', 'email' => 'dara@example.com', 'class' => 'Grade 11-B', 'gender' => 'male', 'parent' => 'Rath Sitha', 'phone' => '098 712 334', 'status' => 'pending'],
                            ['code' => 'STU-1004', 'name' => 'Chanthou Seng', 'email' => 'chanthou@example.com', 'class' => 'Grade 12-A', 'gender' => 'female', 'parent' => 'Seng Kosal', 'phone' => '077 445 109', 'status' => 'active'],
                            ['code' => 'STU-1005', 'name' => 'Panha Lim', 'email' => 'panha@example.com', 'class' => 'Grade 9-C', 'gender' => 'male', 'parent' => 'Lim Vichea', 'phone' => '089 990 123', 'status' => 'active'],
                        ];
                    @endphp

                    @foreach($studentsList as $student)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 pl-6 pr-3 font-mono font-medium text-xs text-indigo-600">
                                {{ $student['code'] }}
                            </td>
                            <td class="py-4 px-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($student['name'], 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 leading-snug">{{ $student['name'] }}</p>
                                        <p class="text-xs text-slate-400">{{ $student['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium">
                                    {{ $student['class'] }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-xs capitalize text-slate-600">
                                {{ $student['gender'] }}
                            </td>
                            <td class="py-4 px-3">
                                <p class="text-xs font-medium text-slate-800">{{ $student['parent'] }}</p>
                                <p class="text-[11px] text-slate-400 font-mono">{{ $student['phone'] }}</p>
                            </td>
                            <td class="py-4 px-3">
                                @if($student['status'] === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Enrolled
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 pl-3 pr-6 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button 
                                        type="button" 
                                        title="View Details" 
                                        class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>
                                    <button 
                                        type="button" 
                                        title="Edit Student" 
                                        class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <button 
                                        type="button" 
                                        @click="deleteModalOpen = true; selectedStudent = '{{ $student['name'] }}'"
                                        title="Delete" 
                                        class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="p-4 px-6 bg-slate-50/50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            <p>Page 1 of 258</p>
            <div class="inline-flex items-center gap-1">
                <button class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 disabled:opacity-50">Previous</button>
                <button class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white font-medium shadow-xs">1</button>
                <button class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600">2</button>
                <button class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600">3</button>
                <button class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600">Next</button>
            </div>
        </div>
    </div>

    <!-- Add Student Modal (Alpine.js) -->
    <div 
        x-show="addModalOpen" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-y-auto"
        role="dialog"
    >
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            <!-- Modal Backdrop -->
            <div 
                x-show="addModalOpen"
                @click="addModalOpen = false" 
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"
            ></div>

            <!-- Modal Content Card -->
            <div 
                x-show="addModalOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-2xl max-w-xl w-full p-6 text-left shadow-xl border border-slate-200/80 my-8 overflow-hidden z-10"
            >
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Add New Student</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Register a student profile with classroom and parent details.</p>
                    </div>
                    <button @click="addModalOpen = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ url('/admin/students') }}" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Full Name</label>
                            <input type="text" name="name" required placeholder="e.g. Sokha Chan" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address</label>
                            <input type="email" name="email" required placeholder="student@school.edu" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none">
                        </div>

                        <!-- Student Code -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Student ID / Code</label>
                            <input type="text" name="student_code" required placeholder="STU-1006" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono">
                        </div>

                        <!-- Classroom -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Assign Classroom</label>
                            <select name="classroom_id" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none">
                                <option value="">Select Class...</option>
                                <option value="1">Grade 10-A</option>
                                <option value="2">Grade 11-B</option>
                                <option value="3">Grade 12-A</option>
                            </select>
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none">
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Gender</label>
                            <select name="gender" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Parent Name -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Guardian Name</label>
                            <input type="text" name="parent_name" placeholder="Parent or Guardian" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none">
                        </div>

                        <!-- Parent Phone -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Guardian Phone</label>
                            <input type="text" name="parent_phone" placeholder="012 345 678" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-mono">
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Home Address</label>
                        <textarea name="address" rows="2" placeholder="Street, Khan, Phnom Penh" class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button 
                            type="button" 
                            @click="addModalOpen = false" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs sm:text-sm font-semibold text-white shadow-sm"
                        >
                            Save Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
