<!-- TAB 3: Digital Faculty ID Card -->
<div x-show="activeTab === 'card'" x-cloak class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Official Smart Faculty Credential</h2>
            <p class="text-xs text-slate-500 mt-0.5">Contactless high-security faculty identification pass issued by Institute Academic Affairs</p>
        </div>
        <div class="flex items-center gap-3">
            <button 
                @click="cardFlipped = !cardFlipped" 
                type="button"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors cursor-pointer"
            >
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span x-text="cardFlipped ? 'Show Front Side' : 'Flip to Back Side'">Flip to Back Side</span>
            </button>
            <button 
                onclick="window.print()" 
                type="button"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.076-.672-2.13-1.28-3.09a8.956 8.956 0 0 0-.964-1.248A8.963 8.963 0 0 1 12 6c3.48 0 6.47 1.974 7.95 4.887a8.96 8.96 0 0 0-.964 1.248 9.07 9.07 0 0 0-1.28 3.09M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5" />
                </svg>
                <span>Print Faculty Card</span>
            </button>
        </div>
    </div>

    <!-- Realistic Physical ID Card Display -->
    <div class="flex justify-center py-6 print-container">
        <div class="w-full max-w-md sm:max-w-lg">
            <!-- Front Side -->
            <div 
                x-show="!cardFlipped" 
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="relative rounded-3xl bg-gradient-to-tr from-slate-950 via-emerald-950 to-teal-900 text-white p-6 sm:p-7 shadow-2xl border border-emerald-400/30 overflow-hidden"
            >
                <!-- Holographic Foil Pattern Accent -->
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-gradient-to-br from-emerald-400/25 via-teal-500/20 to-amber-300/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-teal-500/20 rounded-full blur-2xl pointer-events-none"></div>

                <!-- Card Header -->
                <div class="relative z-10 flex items-center justify-between pb-4 border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-400 to-teal-500 flex items-center justify-center text-white shadow-md">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-sm sm:text-base tracking-wide uppercase text-white">Academic Faculty Card</h3>
                            <p class="text-[10px] text-emerald-300 tracking-wider uppercase font-semibold">Official Staff Credential</p>
                        </div>
                    </div>

                    <!-- Microchip graphic -->
                    <div class="w-10 h-7 rounded-md bg-gradient-to-tr from-amber-300 to-amber-500 border border-amber-200/80 shadow-inner flex items-center justify-center opacity-90">
                        <div class="w-6 h-4 border border-amber-700/40 rounded-xs grid grid-cols-2 gap-0.5 p-0.5">
                            <div class="bg-amber-600/30"></div>
                            <div class="bg-amber-600/30"></div>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="relative z-10 py-5 flex items-center gap-5">
                    <div class="relative shrink-0">
                        <div class="w-24 h-28 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-300 p-0.5 shadow-lg">
                            <div class="w-full h-full bg-slate-900 rounded-[14px] flex flex-col items-center justify-center text-white">
                                <span class="text-3xl font-extrabold font-mono text-emerald-300">{{ strtoupper(substr($facultyName ?: 'FA', 0, 2)) }}</span>
                                <span class="text-[9px] uppercase tracking-wider text-emerald-400/80 mt-1 font-semibold">FACULTY</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 flex-1 min-w-0">
                        <h4 class="text-lg sm:text-xl font-extrabold text-white truncate leading-tight">{{ $facultyName }}</h4>
                        <p class="text-xs text-emerald-300 font-semibold truncate">{{ $qualification ?: 'Faculty Academic Staff' }}</p>
                        <p class="text-[11px] text-slate-300 leading-snug">{{ $specialization ?: 'Instructional Staff' }}</p>

                        <div class="pt-2 grid grid-cols-2 gap-2 text-[10px]">
                            <div>
                                <span class="text-slate-400 block uppercase font-medium">Staff ID</span>
                                <span class="font-mono font-bold text-white text-xs">{{ $staffId }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block uppercase font-medium">Valid Thru</span>
                                <span class="font-mono font-bold text-emerald-300 text-xs">{{ $user?->created_at ? \Illuminate\Support\Carbon::parse($user->created_at)->addYears(2)->format('Y-m-d') : date('Y-12-31') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer: Contactless NFC & Barcode -->
                <div class="relative z-10 pt-4 border-t border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <!-- NFC icon -->
                        <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z" />
                        </svg>
                        <span class="text-[9px] font-mono tracking-widest text-slate-300 uppercase">ACADEMIC SMART PASS</span>
                    </div>

                    <!-- Barcode Simulation -->
                    <div class="flex items-center gap-0.5 bg-white/10 px-2 py-1 rounded-md">
                        <div class="w-0.5 h-5 bg-white"></div>
                        <div class="w-1 h-5 bg-white"></div>
                        <div class="w-0.5 h-5 bg-white"></div>
                        <div class="w-1.5 h-5 bg-white"></div>
                        <div class="w-0.5 h-5 bg-white"></div>
                        <div class="w-1 h-5 bg-white"></div>
                        <div class="w-0.5 h-5 bg-white"></div>
                        <div class="w-1.5 h-5 bg-white"></div>
                        <div class="w-0.5 h-5 bg-white"></div>
                    </div>
                </div>
            </div>

            <!-- Back Side -->
            <div 
                x-show="cardFlipped" 
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="relative rounded-3xl bg-gradient-to-tr from-slate-900 via-emerald-950 to-teal-950 text-white p-6 sm:p-7 shadow-2xl border border-emerald-400/30 overflow-hidden"
            >
                <!-- Magnetic Stripe -->
                <div class="absolute top-6 left-0 right-0 h-10 bg-slate-950 shadow-inner border-y border-white/5"></div>

                <div class="pt-14 space-y-4">
                    <div class="text-[10px] text-slate-400 leading-relaxed space-y-1">
                        <p class="font-bold text-slate-300 uppercase">Terms &amp; Campus Regulations</p>
                        <p>This card is the official property of the academic institution. It is non-transferable and must be presented upon request to authorized campus personnel.</p>
                        <p>If found, please return to the Office of Academic Registrar.</p>
                    </div>

                    <!-- Emergency Security Contacts -->
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 flex items-center justify-between text-[11px]">
                        <div>
                            <span class="text-slate-400 block text-[9px] uppercase font-semibold">Faculty Contact</span>
                            <span class="font-mono font-bold text-emerald-300">{{ $facultyEmail ?: 'N/A' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-400 block text-[9px] uppercase font-semibold">Issuing Authority</span>
                            <span class="font-serif italic text-emerald-200">Academic Affairs</span>
                        </div>
                    </div>

                    <!-- QR Code Scanner Mockup -->
                    <div class="flex items-center justify-between pt-2 border-t border-white/10">
                        <div>
                            <span class="text-[9px] font-mono text-slate-400 uppercase tracking-wider">Pass Verification</span>
                            <p class="text-[10px] text-emerald-400 font-mono">{{ $staffId }}-{{ date('Y') }}-VERIFIED</p>
                        </div>
                        <div class="w-12 h-12 bg-white rounded-lg p-1 flex items-center justify-center">
                            <svg class="w-10 h-10 text-slate-950" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 3h6v6H3V3zm2 2v2h2V5H5zm8-2h6v6h-6V3zm2 2v2h2V5h-2zM3 13h6v6H3v-6zm2 2v2h2v-2H5zm13-2h3v3h-3v-3zm-5 0h3v1h-3v-1zm5 5h3v3h-3v-3zm-2-2h2v2h-2v-2zm-3 2h2v3h-2v-3zm0-4h2v2h-2v-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
