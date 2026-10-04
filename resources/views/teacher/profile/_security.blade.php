<!-- TAB 4: Account & Security -->
<div x-show="activeTab === 'security'" x-cloak class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Password Update Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Change Account Password</h2>
                            <p class="text-xs text-slate-400">Ensure your teacher portal account is secured with a strong passphrase</p>
                        </div>
                    </div>
                </div>

                <form 
                    method="POST" 
                    action="{{ route('teacher.profile.password') }}" 
                    x-data="{ showCurrent: false, showNew: false, showConfirm: false }"
                    class="p-5 sm:p-6 space-y-4"
                >
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Current Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                :type="showCurrent ? 'text' : 'password'" 
                                name="current_password" 
                                id="current_password" 
                                required
                                class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border {{ $errors->has('current_password') ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm text-slate-800"
                                placeholder="Enter your current account password"
                            >
                            <button 
                                type="button" 
                                @click="showCurrent = !showCurrent" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                title="Toggle password visibility"
                            >
                                <svg x-show="!showCurrent" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <svg x-show="showCurrent" x-cloak class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                New Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    :type="showNew ? 'text' : 'password'" 
                                    name="password" 
                                    id="password" 
                                    required
                                    class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm text-slate-800"
                                    placeholder="Min. 8 characters"
                                >
                                <button 
                                    type="button" 
                                    @click="showNew = !showNew" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                    title="Toggle password visibility"
                                >
                                    <svg x-show="!showNew" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <svg x-show="showNew" x-cloak class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Confirm New Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    :type="showConfirm ? 'text' : 'password'" 
                                    name="password_confirmation" 
                                    id="password_confirmation" 
                                    required
                                    class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border {{ $errors->has('password_confirmation') ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm text-slate-800"
                                    placeholder="Repeat new password"
                                >
                                <button 
                                    type="button" 
                                    @click="showConfirm = !showConfirm" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                    title="Toggle password visibility"
                                >
                                    <svg x-show="!showConfirm" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <svg x-show="showConfirm" x-cloak class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 text-xs text-slate-500 flex items-start gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                        <span>Passwords must contain at least 8 characters. We recommend combining letters, numbers, and symbols.</span>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold shadow-md transition-colors cursor-pointer"
                        >
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right 1 Col: Security Summary & 2FA Status -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 space-y-4">
                <h3 class="text-sm font-bold text-slate-900">Security Checkup</h3>
                
                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="font-medium text-slate-700">Account Role</span>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-semibold">Teacher</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="font-medium text-slate-700">Email Verification</span>
                        <span class="text-emerald-600 font-semibold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            Verified
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="font-medium text-slate-700">Two-Factor Authentication</span>
                        <span class="text-slate-400 font-medium">Standard SSO</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="font-medium text-slate-700">Current Session</span>
                        <span class="text-slate-600 font-mono">Active (This device)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
