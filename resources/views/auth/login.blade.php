<x-guest-layout>
    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-screen bg-slate-950">

        <!-- LEFT PANEL: FinStats Aesthetic Showcase (Hidden on Mobile) -->
        <div
            class="hidden lg:flex lg:col-span-5 bg-slate-900 relative flex-col justify-between p-12 overflow-hidden border-r border-slate-800">
            <!-- Subtle futuristic background glowing blobs -->
            <div
                class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-emerald-500/10 blur-[100px] pointer-events-none">
            </div>
            <div
                class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-teal-500/10 blur-[100px] pointer-events-none">
            </div>

            <!-- Top branding element -->
            <div class="flex items-center gap-3 relative z-10">
                <div
                    class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i class="fa-solid fa-chart-pie text-white text-lg"></i>
                </div>
                <span class="font-bold text-2xl text-white tracking-tight font-plus">Fin<span
                        class="text-emerald-400">Stats</span></span>
            </div>

            <!-- Center visual illustration using Tailwind cards (Ultra Light and Elegant) -->
            <div class="my-auto relative z-10 space-y-8">
                <div class="space-y-3">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold">
                        <i class="fa-solid fa-shield-halved"></i> Secure Encryption Active
                    </div>
                    <h1 class="text-4xl font-extrabold text-white tracking-tight leading-tight font-plus">
                        Welcome Back to <br>Smart Budgeting.
                    </h1>
                    <p class="text-slate-400 text-base leading-relaxed max-w-sm">
                        Log in to view your real-time cash flow, run automated monthly calculations, and check your
                        asset trends.
                    </p>
                </div>

                <!-- Interactive Visual Stats Box -->
                <div
                    class="bg-slate-800/40 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 shadow-xl space-y-4 max-w-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 text-sm">Monthly Balance Tracker</span>
                        <span
                            class="text-emerald-400 text-xs font-semibold flex items-center gap-1 bg-emerald-500/10 px-2 py-0.5 rounded">
                            <i class="fa-solid fa-arrow-trend-up"></i> +18.4%
                        </span>
                    </div>
                    <div class="text-3xl font-bold text-white tracking-tight">
                        Rp 14.250.000
                    </div>
                    <!-- Simple simulated vector bars -->
                    <div class="flex items-end gap-2 h-16 pt-2">
                        <div class="bg-slate-700 w-full h-[40%] rounded-sm"></div>
                        <div class="bg-slate-700 w-full h-[55%] rounded-sm"></div>
                        <div class="bg-slate-700 w-full h-[35%] rounded-sm"></div>
                        <div class="bg-emerald-500/80 w-full h-[85%] rounded-sm shadow-lg shadow-emerald-500/20"></div>
                    </div>
                </div>
            </div>

            <!-- Bottom credits info -->
            <div class="text-xs text-slate-500 relative z-10">
                &copy; 2026 FinStats Global. All rights secured.
            </div>
        </div>

        <!-- RIGHT PANEL: Beautiful Minimalist Form -->
        <div
            class="col-span-1 lg:col-span-7 flex flex-col justify-center px-6 sm:px-16 lg:px-24 xl:px-32 py-12 relative">
            <div class="max-w-md w-full mx-auto space-y-8">

                <!-- Header text -->
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold text-white tracking-tight font-plus">Sign In</h2>
                    <p class="text-slate-400 text-sm">
                        Don't have an account yet?
                        <a href="{{ route('register') }}"
                            class="text-emerald-400 hover:text-emerald-300 font-medium transition-colors">Create free
                            account</a>
                    </p>
                </div>

                <!-- Session Status Alert (Laravel default feedback) -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Actual Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div class="space-y-1.5">
                        <label for="email"
                            class="block text-xs font-semibold text-slate-300 tracking-wider uppercase">Email
                            Address</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-regular fa-envelope"></i>
                            </div>
                            <input id="email"
                                class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-white placeholder-slate-500 rounded-xl transition-all duration-200"
                                type="email" name="email" :value="old('email')" placeholder="you@example.com"
                                required autofocus autocomplete="username" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center">
                            <label for="password"
                                class="block text-xs font-semibold text-slate-300 tracking-wider uppercase">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-xs text-slate-400 hover:text-emerald-400 transition-colors">Forgot
                                    password?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input id="password"
                                class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-white placeholder-slate-500 rounded-xl transition-all duration-200"
                                type="password" name="password" placeholder="••••••••" required
                                autocomplete="current-password" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox"
                                class="rounded bg-slate-900 border-slate-800 text-emerald-500 focus:ring-emerald-500/20 focus:ring-offset-slate-950"
                                name="remember">
                            <span
                                class="ms-2.5 text-sm text-slate-400 group-hover:text-slate-300 transition-colors">Remember
                                this device</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full flex justify-center items-center gap-2 py-3.5 px-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold rounded-xl shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/35 focus:ring-2 focus:ring-emerald-500/30 transform hover:-translate-y-0.5 transition-all duration-150">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In to Dashboard
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-guest-layout>
