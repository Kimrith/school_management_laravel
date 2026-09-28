@extends('layouts.admin')

@section('title', 'Suspended Faculty')
@section('page_title', 'Suspended Faculty')

@section('content')
<div class="space-y-6">
    <!-- Header with Breadcrumbs -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-1">
                <a href="{{ route('admin.teachers.index') }}" class="hover:text-indigo-600 transition-colors">Faculty Directory</a>
                <span>/</span>
                <span class="text-slate-700">Suspended Accounts</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Suspended Faculty Members</h1>
            <p class="text-sm text-slate-500 mt-1">Review faculty members whose teaching permissions and portal access have been paused.</p>
        </div>

        <div class="flex items-center gap-3">
            <a 
                href="{{ route('admin.teachers.index') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-colors"
            >
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Back to All Faculty</span>
            </a>
        </div>
    </div>

    <!-- Top Status Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200/80 overflow-x-auto pb-px">
        <a 
            href="{{ route('admin.teachers.index') }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-800 hover:border-slate-300 transition-colors whitespace-nowrap"
        >
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Active Faculty</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold font-mono bg-slate-100 text-slate-600">
                {{ $activeCount }}
            </span>
        </a>

        <a 
            href="{{ route('admin.teachers.suspended') }}" 
            class="flex items-center gap-2 px-4 py-3 border-b-2 border-rose-600 font-bold text-sm text-rose-600 transition-colors whitespace-nowrap"
        >
            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            <span>Suspended Accounts</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold font-mono bg-rose-50 text-rose-700 border border-rose-200">
                {{ $suspendedCount }}
            </span>
        </a>
    </div>

    <!-- Alert Banner -->
    <div class="bg-rose-50/70 border border-rose-100 rounded-2xl p-4 flex items-start gap-3.5 shadow-2xs">
        <div class="w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-xs">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
        </div>
        <div class="text-xs">
            <h4 class="font-bold text-rose-950">Suspended Instructor Access</h4>
            <p class="text-rose-800/90 mt-0.5 leading-relaxed">
                Faculty listed here cannot log in, manage courses, or submit student assessments. You can reinstate accounts with one click to restore full privileges.
            </p>
        </div>
    </div>

    <!-- Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.teachers.suspended') }}" class="flex items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-80">
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search suspended faculty..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500"
                >
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700 transition-colors">
                Filter
            </button>

            @if(request('search'))
                <a href="{{ route('admin.teachers.suspended') }}" class="text-xs text-rose-600 hover:underline">
                    Clear
                </a>
            @endif
        </form>

        <span class="text-xs text-slate-500">
            Total Suspended: <span class="font-bold text-slate-800">{{ $teachers->total() }}</span>
        </span>
    </div>

    <!-- Suspended Teachers Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <th scope="col" class="py-3.5 pl-6 pr-3">Instructor</th>
                        <th scope="col" class="py-3.5 px-3">Qualification</th>
                        <th scope="col" class="py-3.5 px-3">Specialization</th>
                        <th scope="col" class="py-3.5 px-3">Contact</th>
                        <th scope="col" class="py-3.5 px-3">Status</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($teachers as $teacher)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 pl-6 pr-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 font-bold text-xs flex items-center justify-center shrink-0 border border-rose-200/60">
                                        {{ strtoupper(substr($teacher->user->name ?? 'TC', 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 leading-snug">{{ $teacher->user->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-slate-400">{{ $teacher->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">
                                    {{ $teacher->qualification ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-xs font-medium text-slate-700">
                                {{ $teacher->specialization ?? 'General' }}
                            </td>
                            <td class="py-4 px-3 font-mono text-xs text-slate-600">
                                {{ $teacher->phone ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Suspended
                                </span>
                            </td>
                            <td class="py-4 pl-3 pr-6 text-right">
                                <div class="inline-flex items-center gap-2 justify-end">
                                    <!-- Reinstate Action -->
                                    <form 
                                        method="POST" 
                                        action="{{ route('admin.teachers.toggle-status', $teacher->id) }}" 
                                        onsubmit="return confirm('Reinstate faculty member {{ $teacher->user->name ?? '' }}?')"
                                        class="inline-block"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <button 
                                            type="submit" 
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold transition-colors cursor-pointer"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>
                                            <span>Reinstate</span>
                                        </button>
                                    </form>

                                    <!-- Edit -->
                                    <a 
                                        href="{{ route('admin.teachers.edit', $teacher->id) }}" 
                                        class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all"
                                        title="Edit Profile"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </a>

                                    <!-- Delete -->
                                    <form 
                                        method="POST" 
                                        action="{{ route('admin.teachers.destroy', $teacher->id) }}" 
                                        onsubmit="return confirm('Permanently delete this faculty member?')"
                                        class="inline-block"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all cursor-pointer"
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
                            <td colspan="6" class="py-14 text-center">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-3 shadow-xs">
                                        ✨
                                    </div>
                                    <h3 class="text-base font-bold text-slate-800">No suspended faculty</h3>
                                    <p class="text-xs text-slate-400 mt-1">All registered instructors currently have active teaching and portal status.</p>
                                    <a 
                                        href="{{ route('admin.teachers.index') }}" 
                                        class="mt-4 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors"
                                    >
                                        Return to Faculty Directory
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @include('share.pagination', ['paginator' => $teachers])
    </div>
</div>
@endsection
