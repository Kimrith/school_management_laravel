@php
    $paginator = $paginator ?? $items ?? $students ?? $invoices ?? $classrooms ?? $teachers ?? $subjects ?? $levels ?? $attendances ?? null;
@endphp

@if ($paginator && $paginator->total() > 0)
    <div class="p-4 px-6 bg-slate-50/50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
        <!-- Entry Count Info -->
        <p class="font-normal text-slate-500">
            Showing 
            <span class="font-semibold text-slate-800">{{ $paginator->firstItem() }}</span> 
            to 
            <span class="font-semibold text-slate-800">{{ $paginator->lastItem() }}</span> 
            of 
            <span class="font-semibold text-slate-800">{{ $paginator->total() }}</span> 
            entries
        </p>

        <!-- Pagination Controls -->
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-1">
            @if ($paginator->hasPages())
                {{-- Previous Page Button --}}
                @if ($paginator->onFirstPage())
                    <span 
                        aria-disabled="true" 
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 bg-slate-50/80 border border-slate-200/50 cursor-not-allowed"
                        title="Previous page"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </span>
                @else
                    <a 
                        href="{{ $paginator->previousPageUrl() }}" 
                        rel="prev" 
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-100/80 border border-slate-200/80 shadow-2xs transition-colors"
                        title="Previous page"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </a>
                @endif

                {{-- Page Links --}}
                @foreach ($paginator->linkCollection()->slice(1, -1) as $link)
                    @if ($link['active'])
                        <span 
                            aria-current="page" 
                            class="min-w-[32px] h-8 px-2.5 rounded-lg flex items-center justify-center font-semibold text-xs text-white bg-slate-900 shadow-2xs select-none"
                        >
                            {{ $link['label'] }}
                        </span>
                    @elseif ($link['url'] === null)
                        <span class="min-w-[28px] h-8 flex items-center justify-center text-slate-400 text-xs tracking-widest select-none">
                            &hellip;
                        </span>
                    @else
                        <a 
                            href="{{ $link['url'] }}" 
                            class="min-w-[32px] h-8 px-2.5 rounded-lg flex items-center justify-center font-medium text-xs text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 transition-colors"
                        >
                            {{ $link['label'] }}
                        </a>
                    @endif
                @endforeach

                {{-- Next Page Button --}}
                @if ($paginator->hasMorePages())
                    <a 
                        href="{{ $paginator->nextPageUrl() }}" 
                        rel="next" 
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-100/80 border border-slate-200/80 shadow-2xs transition-colors"
                        title="Next page"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                @else
                    <span 
                        aria-disabled="true" 
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 bg-slate-50/80 border border-slate-200/50 cursor-not-allowed"
                        title="Next page"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </span>
                @endif
            @else
                {{-- Visible Controls for Single Page (Always show navigation controls) --}}
                <span 
                    aria-disabled="true" 
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 bg-slate-50/80 border border-slate-200/50 cursor-not-allowed"
                    title="Previous page"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </span>
                <span 
                    aria-current="page" 
                    class="min-w-[32px] h-8 px-2.5 rounded-lg flex items-center justify-center font-semibold text-xs text-white bg-slate-900 shadow-2xs select-none"
                >
                    1
                </span>
                <span 
                    aria-disabled="true" 
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 bg-slate-50/80 border border-slate-200/50 cursor-not-allowed"
                    title="Next page"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </span>
            @endif
        </nav>
    </div>
@endif