{{-- 
    Global Loading Preloader & Dynamic Fetch Indicator
    School Management System
--}}

<!-- Top Slim Indeterminate Progress Bar -->
<div id="global-top-progress" class="fixed top-0 left-0 right-0 h-1 bg-indigo-600/20 z-[10000] overflow-hidden pointer-events-none transition-opacity duration-300 opacity-100">
    <div class="h-full bg-gradient-to-r from-indigo-500 via-sky-400 to-indigo-600 animate-[progress_1.2s_ease-in-out_infinite] w-full origin-left"></div>
</div>

<!-- Main Preloader & Data Fetching Overlay -->
<div 
    id="global-page-loader" 
    class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-slate-900/60 backdrop-blur-md transition-all duration-300 opacity-100"
    role="status"
    aria-live="polite"
    aria-label="Loading page and data"
>
    <!-- Card Container -->
    <div class="relative bg-white/95 backdrop-blur-xl border border-white/40 shadow-2xl rounded-3xl p-8 max-w-sm w-11/12 mx-auto flex flex-col items-center text-center transform transition-all duration-300 scale-100 animate-in fade-in zoom-in-95">
        
        <!-- Animated Ring & Icon -->
        <div class="relative w-20 h-20 flex items-center justify-center mb-5">
            <!-- Outer Pulsing Glow -->
            <div class="absolute inset-0 rounded-full bg-indigo-500/20 animate-ping"></div>
            
            <!-- Rotating Gradient Border Ring -->
            <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-indigo-600 border-r-sky-500 border-b-indigo-400 animate-spin"></div>
            
            <!-- Inner Center Icon/Badge -->
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-sky-500 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg class="w-7 h-7 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 1-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 1-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                </svg>
            </div>
        </div>

        <!-- Dynamic Loading Title & Message -->
        <h3 id="global-loader-title" class="text-base font-bold text-slate-800 tracking-tight">
            Connecting to Database
        </h3>
        <p id="global-loader-msg" class="text-xs text-slate-500 mt-1 font-medium">
            Please wait while records are being retrieved...
        </p>

        <!-- Dots Animation -->
        <div class="flex items-center gap-1.5 mt-4">
            <span class="w-2 h-2 rounded-full bg-indigo-600 animate-bounce [animation-delay:-0.3s]"></span>
            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-bounce [animation-delay:-0.15s]"></span>
            <span class="w-2 h-2 rounded-full bg-sky-500 animate-bounce"></span>
        </div>
    </div>
</div>

<style>
    @keyframes progress {
        0% { transform: translateX(-100%); }
        50% { transform: translateX(0%); }
        100% { transform: translateX(100%); }
    }
</style>

<script>
    (function() {
        const loader = document.getElementById('global-page-loader');
        const progressBar = document.getElementById('global-top-progress');
        const loaderTitle = document.getElementById('global-loader-title');
        const loaderMsg = document.getElementById('global-loader-msg');

        let isHidden = false;

        /**
         * Hide the loader with smooth fade-out
         */
        window.hideLoading = function() {
            if (!loader || isHidden) return;
            loader.classList.add('opacity-0', 'pointer-events-none');
            if (progressBar) progressBar.classList.add('opacity-0');
            
            setTimeout(() => {
                loader.style.display = 'none';
                if (progressBar) progressBar.style.display = 'none';
                isHidden = true;
            }, 300);
        };

        /**
         * Show the loader with optional custom title and message
         * @param {string} title
         * @param {string} message
         */
        window.showLoading = function(title = 'Loading...', message = 'Please wait while records are being processed...') {
            if (!loader) return;
            isHidden = false;
            if (loaderTitle) loaderTitle.textContent = title;
            if (loaderMsg) loaderMsg.textContent = message;

            loader.style.display = 'flex';
            if (progressBar) progressBar.style.display = 'block';

            // Force reflow for CSS transitions
            void loader.offsetWidth;
            loader.classList.remove('opacity-0', 'pointer-events-none');
            if (progressBar) progressBar.classList.remove('opacity-0');
        };

        // 1. Initial Page Load: Hide as soon as DOM and assets are fully loaded
        window.addEventListener('load', function() {
            window.hideLoading();
        });

        // 2. Fallback timeout: Ensure loader never blocks the user longer than 2.5 seconds if an asset is slow
        setTimeout(function() {
            window.hideLoading();
        }, 2500);

        // 3. Listen to Alpine.js or Custom Events:
        // Dispatch in Alpine: $dispatch('show-loading', { title: 'Querying DB', message: 'Fetching filtered classes...' })
        // Dispatch in Alpine: $dispatch('hide-loading')
        window.addEventListener('show-loading', function(event) {
            const detail = event.detail || {};
            window.showLoading(detail.title || 'Loading...', detail.message || 'Processing your request...');
        });

        window.addEventListener('hide-loading', function() {
            window.hideLoading();
        });

        // 4. Automatically show loader when submitting standard forms (e.g. Save, Delete, Filter)
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form && !form.hasAttribute('data-no-loader')) {
                window.showLoading('Saving Changes...', 'Updating records in database...');
            }
        });
    })();
</script>
