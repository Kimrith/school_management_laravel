<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Student Portal') - {{ config('app.name', 'School Management System') }}</title>

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
    <div class="min-h-full flex flex-col">
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
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 to-blue-600 flex items-center justify-center text-white shadow-md shadow-sky-500/25">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-bold tracking-tight text-slate-900 group-hover:text-sky-600 transition-colors">EduStudent</span>
                            <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Student Portal</span>
                        </div>
                    </a>

                    <button @click="sidebarOpen = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg lg:hidden">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1.5 overflow-y-auto">
                    <p class="px-3 pb-2 text-[11px] font-semibold tracking-wider text-slate-400 uppercase">My Academics</p>

                    <a 
                        href="{{ url('/student/dashboard') }}" 
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 bg-sky-50 text-sky-700 shadow-xs"
                    >
                        <svg class="w-5 h-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        <span>My Dashboard</span>
                    </a>

                    <a 
                        href="{{ url('/pdf/report-card') }}" 
                        target="_blank"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 transition-colors"
                    >
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <span>Download Report Card</span>
                    </a>
                </nav>
            </div>

            <!-- Current Student Info -->
            <div class="p-4 border-t border-slate-100">
                <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-200/60">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-lg bg-sky-600 text-white font-semibold text-sm flex items-center justify-center shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'ST', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name ?? 'Student User' }}</p>
                            <p class="text-xs text-slate-400 truncate">STU-1001 &bull; Grade 10-A</p>
                        </div>
                    </div>

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
                        <span>Student Portal</span>
                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                        <span class="text-slate-900 font-semibold">@yield('page_title', 'Dashboard')</span>
                    </nav>
                </div>

                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                        Active Student
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
