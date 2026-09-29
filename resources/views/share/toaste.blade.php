@php
    $initialToast = null;
    if (session('success')) {
        $initialToast = ['type' => 'success', 'title' => 'Success', 'message' => session('success')];
    } elseif (session('error')) {
        $initialToast = ['type' => 'error', 'title' => 'Error', 'message' => session('error')];
    } elseif (session('warning')) {
        $initialToast = ['type' => 'warning', 'title' => 'Warning', 'message' => session('warning')];
    } elseif (session('info')) {
        $initialToast = ['type' => 'info', 'title' => 'Notice', 'message' => session('info')];
    } elseif (isset($errors) && $errors->any()) {
        $initialToast = ['type' => 'error', 'title' => 'Validation Error', 'message' => $errors->first()];
    }
@endphp

<script>
    function toastNotification() {
        return {
            toasts: [],
            addToast(toast) {
                const id = Date.now() + Math.random();
                const newToast = {
                    id: id,
                    type: toast.type || 'success',
                    title: toast.title || (toast.type === 'error' ? 'Error' : 'Success'),
                    message: toast.message || '',
                    show: false,
                    progress: 100
                };
                this.toasts.push(newToast);

                // Animate entrance
                setTimeout(() => {
                    const item = this.toasts.find(t => t.id === id);
                    if (item) item.show = true;
                }, 50);

                // Progress bar & auto dismiss (4.5s)
                const duration = 4500;
                const interval = 50;
                const step = (interval / duration) * 100;
                const timer = setInterval(() => {
                    const item = this.toasts.find(t => t.id === id);
                    if (!item) {
                        clearInterval(timer);
                        return;
                    }
                    item.progress -= step;
                    if (item.progress <= 0) {
                        clearInterval(timer);
                        this.removeToast(id);
                    }
                }, interval);
            },
            removeToast(id) {
                const index = this.toasts.findIndex(t => t.id === id);
                if (index > -1) {
                    this.toasts[index].show = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 300);
                }
            },
            init() {
                @if ($initialToast)
                    this.addToast(@json($initialToast));
                @endif

                window.addEventListener('notify', (e) => {
                    if (e.detail) this.addToast(e.detail);
                });
                window.addEventListener('toast', (e) => {
                    if (e.detail) this.addToast(e.detail);
                });
            }
        };
    }

    if (window.Alpine) {
        Alpine.data('toastNotification', toastNotification);
    } else {
        document.addEventListener('alpine:init', function() {
            Alpine.data('toastNotification', toastNotification);
        });
    }
</script>

<!-- Toast Notification Container (Fixed Top-Right Popup) -->
<div 
    x-data="toastNotification()"
    class="fixed top-5 right-5 z-50 pointer-events-none flex flex-col gap-3 max-w-sm w-full px-4 sm:px-0"
    role="region"
    aria-label="Notifications"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div 
            x-show="toast.show"
            x-cloak
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="pointer-events-auto bg-white/95 backdrop-blur-md rounded-2xl shadow-xl shadow-slate-900/10 border border-slate-100/90 overflow-hidden relative"
        >
            <div class="p-4 flex items-start gap-3.5">
                <!-- Status Icon Badge -->
                <div 
                    class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 shadow-2xs border"
                    :class="{
                        'bg-emerald-50 text-emerald-600 border-emerald-100': toast.type === 'success',
                        'bg-rose-50 text-rose-600 border-rose-100': toast.type === 'error',
                        'bg-amber-50 text-amber-600 border-amber-100': toast.type === 'warning',
                        'bg-indigo-50 text-indigo-600 border-indigo-100': toast.type === 'info'
                    }"
                >
                    <!-- Success Icon -->
                    <template x-if="toast.type === 'success'">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </template>
                    <!-- Error Icon -->
                    <template x-if="toast.type === 'error'">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </template>
                    <!-- Warning Icon -->
                    <template x-if="toast.type === 'warning'">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 12.376Z" />
                        </svg>
                    </template>
                    <!-- Info Icon -->
                    <template x-if="toast.type === 'info'">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                    </template>
                </div>

                <!-- Text Content -->
                <div class="flex-1 min-w-0 pt-0.5">
                    <h5 
                        class="text-xs font-bold capitalize tracking-tight"
                        :class="{
                            'text-emerald-950': toast.type === 'success',
                            'text-rose-950': toast.type === 'error',
                            'text-amber-950': toast.type === 'warning',
                            'text-indigo-950': toast.type === 'info'
                        }"
                        x-text="toast.title"
                    ></h5>
                    <p class="text-xs text-slate-600 mt-0.5 leading-relaxed font-medium break-words" x-text="toast.message"></p>
                </div>

                <!-- Dismiss Button -->
                <button 
                    type="button"
                    @click="removeToast(toast.id)"
                    class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors shrink-0 cursor-pointer"
                    title="Dismiss"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Auto-dismiss Progress Bar -->
            <div class="h-1 w-full bg-slate-100 overflow-hidden">
                <div 
                    class="h-full transition-all duration-75 ease-linear"
                    :class="{
                        'bg-emerald-500': toast.type === 'success',
                        'bg-rose-500': toast.type === 'error',
                        'bg-amber-500': toast.type === 'warning',
                        'bg-indigo-500': toast.type === 'info'
                    }"
                    :style="`width: ${toast.progress}%`"
                ></div>
            </div>
        </div>
    </template>
</div>
