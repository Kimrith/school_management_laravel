@extends('layouts.admin')

@section('title', 'Suspended Students')
@section('page_title', 'Suspended Students')

@section('content')
<div class="space-y-6">
    <!-- Success Flash Alert -->
    @if(session('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl flex items-center justify-between shadow-xs"
        >
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 p-1 rounded-lg">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Suspended Students</h1>
            <p class="text-sm text-slate-500 mt-1">Review locked student accounts, review reasons, or reinstate access.</p>
        </div>
    </div>

    <!-- Status Tabs: Active vs Suspended -->
    <div class="flex items-center gap-2 border-b border-slate-200/80">
        <a 
            href="{{ route('admin.students.index') }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 font-medium text-sm border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 transition-colors"
        >
            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            <span>Active Students</span>
            <span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-600 font-bold font-mono">
                {{ $activeCount ?? 0 }}
            </span>
        </a>

        <a 
            href="{{ route('admin.students.suspended') }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 font-semibold text-sm border-rose-600 text-rose-600 transition-colors"
        >
            <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
            <span>Suspended Students</span>
            <span class="px-2 py-0.5 rounded-full text-xs bg-rose-50 text-rose-700 font-bold font-mono border border-rose-200">
                {{ $suspendedCount ?? $students->total() }}
            </span>
        </a>
    </div>

    <!-- Alert / Notice Banner -->
    <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200/80 flex items-start gap-3">
        <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
        </div>
        <div class="text-xs text-amber-900 leading-relaxed">
            <p class="font-bold text-sm">Suspended Status Policy</p>
            <p class="mt-0.5 text-amber-800">Suspended students cannot log into the student portal, access exam marks, or check class schedules. You can reinstate a student at any time using the <strong>Reinstate</strong> action button.</p>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.students.suspended') }}" class="flex items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-72">
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search suspended student..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                >
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700 transition-colors">
                Search
            </button>

            @if(request('search'))
                <a href="{{ route('admin.students.suspended') }}" class="text-xs text-rose-600 hover:underline">
                    Clear
                </a>
            @endif
        </form>

        <div class="text-xs text-slate-500">
            <span>Showing <span class="font-semibold text-slate-800">{{ $students->count() }}</span> suspended records</span>
        </div>
    </div>

    <!-- Suspended Students Table -->
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
                    @forelse($students as $student)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 pl-6 pr-3 font-mono font-bold text-xs text-slate-600">
                                {{ $student->student_code }}
                            </td>
                            <td class="py-4 px-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 font-bold text-xs flex items-center justify-center shrink-0 border border-rose-200/70">
                                        {{ strtoupper(substr($student->user->name ?? 'ST', 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 leading-snug">{{ $student->user->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-slate-400">{{ $student->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">
                                    {{ $student->classroom->name ?? 'Unassigned' }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-xs capitalize font-medium text-slate-600">
                                {{ $student->gender }}
                            </td>
                            <td class="py-4 px-3">
                                <p class="text-xs font-semibold text-slate-800">{{ $student->parent_name ?? 'N/A' }}</p>
                                <p class="text-[11px] text-slate-400 font-mono">{{ $student->parent_phone ?? 'N/A' }}</p>
                            </td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Suspended
                                </span>
                            </td>
                            <td class="py-4 pl-3 pr-6 text-right">
                                <div class="inline-flex items-center gap-2 justify-end">
                                    <!-- Reinstate Student Action -->
                                    <form 
                                        method="POST" 
                                        action="{{ route('admin.students.toggle-status', $student->id) }}" 
                                        onsubmit="return confirm('Reinstate student {{ $student->student_code }} ({{ $student->user->name ?? '' }}) and restore full portal access?')"
                                        class="inline-block"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <button 
                                            type="submit" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-semibold transition-all cursor-pointer" 
                                            title="Reinstate Student"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>
                                            <span>Reinstate</span>
                                        </button>
                                    </form>

                                    <!-- Edit Link -->
                                    <a 
                                        href="{{ route('admin.students.edit', $student->id) }}" 
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" 
                                        title="Edit Student"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </a>

                                    <!-- Permanent Delete Action -->
                                    <form 
                                        method="POST" 
                                        action="{{ route('admin.students.destroy', $student->id) }}" 
                                        onsubmit="return confirm('Permanently delete student {{ $student->student_code }}? This action cannot be undone.')"
                                        class="inline-block"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all cursor-pointer" 
                                            title="Delete Record"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-14 text-center">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-3 shadow-xs">
                                        🛡️
                                    </div>
                                    <h3 class="text-base font-bold text-slate-800">No suspended students</h3>
                                    <p class="text-xs text-slate-400 mt-1">All enrolled students currently maintain active standing in good order.</p>
                                    <a 
                                        href="{{ route('admin.students.index') }}" 
                                        class="mt-4 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors"
                                    >
                                        Back to Active Students
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @include('share.pagination', ['paginator' => $students])
    </div>
</div>
@endsection
