<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Teacher Portal') - {{ config('app.name', 'School Management System') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="h-full antialiased text-slate-800" x-data="{ sidebarOpen: false }">
    <!-- Global Page & Database Query Preloader -->
    @include('share.loading')

    <div class="min-h-full flex flex-col">
        <!-- Mobile Sidebar Backdrop -->
        <div 
            x-show="sidebarOpen" 
            x-cloak
            @click="sidebarOpen = false" 
            class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs lg:hidden"
        ></div>

        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200/80 flex flex-col justify-between transition-transform duration-300 ease-in-out lg:z-30 shadow-sm"
        >
            <div class="flex flex-col">
                <div class="h-18 px-6 flex items-center justify-between border-b border-slate-100">
                    <a href="{{ url('/teacher/dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center text-white shadow-md shadow-emerald-500/25">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-bold tracking-tight text-slate-900 group-hover:text-emerald-600 transition-colors">EduTeacher</span>
                            <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Faculty Portal</span>
                        </div>
                    </a>

                    <button @click="sidebarOpen = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg lg:hidden">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Teacher Navigation -->
                <nav class="p-4 space-y-1.5 overflow-y-auto">
                    <p class="px-3 pb-2 text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Teaching Workspace</p>

                    <!-- Dashboard / Overview -->
                    @php $isDash = request()->is('teacher/dashboard') || request()->is('teacher'); @endphp
                    <a 
                        href="{{ url('/teacher/dashboard') }}" 
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ $isDash ? 'bg-emerald-50 text-emerald-700 shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}"
                    >
                        <svg class="w-5 h-5 {{ $isDash ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Take Attendance -->
                    @php $isAtt = request()->is('teacher/attendance*'); @endphp
                    <a 
                        href="{{ url('/teacher/attendance') }}" 
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ $isAtt ? 'bg-emerald-50 text-emerald-700 shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}"
                    >
                        <svg class="w-5 h-5 {{ $isAtt ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span>Mark Attendance</span>
                    </a>

                    <!-- Input Grades -->
                    @php $isGrades = request()->is('teacher/grades*'); @endphp
                    <a 
                        href="{{ url('/teacher/grades') }}" 
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ $isGrades ? 'bg-emerald-50 text-emerald-700 shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}"
                    >
                        <svg class="w-5 h-5 {{ $isGrades ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <span>Exam Grades</span>
                    </a>

                    <!-- Profile -->
                    @php $isProfile = request()->is('teacher/profile*'); @endphp
                    <a 
                        href="{{ route('teacher.profile.index') }}" 
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ $isProfile ? 'bg-emerald-50 text-emerald-700 shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}"
                    >
                        <svg class="w-5 h-5 {{ $isProfile ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        <span>Faculty Profile</span>
                    </a>
                </nav>
            </div>

            <!-- Current Faculty Profile -->
            <div class="p-4 border-t border-slate-100">
                <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-200/60">
                    <a href="{{ route('teacher.profile.index') }}" class="flex items-center gap-3 min-w-0 group">
                        <div class="w-9 h-9 rounded-lg bg-emerald-600 group-hover:bg-emerald-700 text-white font-semibold text-sm flex items-center justify-center shrink-0 transition-colors">
                            {{ strtoupper(substr(auth()->user()->name ?? 'Prof', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-800 group-hover:text-emerald-700 truncate transition-colors">{{ auth()->user()->name ?? 'Faculty Member' }}</p>
                            <p class="text-xs text-slate-400 truncate">Teacher Portal</p>
                        </div>
                    </a>

                    <form method="POST" action="{{ Route::has('logout') ? route('logout') : url('/logout') }}">
                        @csrf
                        <button type="submit" title="Logout" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg cursor-pointer">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="lg:pl-72 flex flex-col flex-1">
            <header class="sticky top-0 z-20 h-18 bg-white/90 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="p-2 -ml-2 text-slate-500 hover:bg-slate-100 rounded-xl lg:hidden">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <nav class="flex items-center gap-2 text-sm text-slate-500 font-medium">
                        <span>Teacher Portal</span>
                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                        <span class="text-slate-900 font-semibold">@yield('page_title', 'Classroom')</span>
                    </nav>
                </div>

                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Faculty Active
                    </span>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
