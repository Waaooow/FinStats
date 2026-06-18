<x-guest-layout>
    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-screen bg-slate-950">

        <!-- LEFT PANEL: Feature highlights -->
        <div
            class="hidden lg:flex lg:col-span-5 bg-slate-900 relative flex-col justify-between p-12 overflow-hidden border-r border-slate-800">
            <!-- Subtle glowing gradient background -->
            <div
                class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-emerald-500/10 blur-[100px] pointer-events-none">
            </div>
            <div
                class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-teal-500/10 blur-[100px] pointer-events-none">
            </div>

            <!-- Logo branding -->
            <div class="flex items-center gap-3 relative z-10">
                <div
                    class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i class="fa-solid fa-chart-pie text-white text-lg"></i>
                </div>
                <span class="font-bold text-2xl text-white tracking-tight font-plus">Fin<span
                        class="text-emerald-400">Stats</span></span>
            </div>

            <!-- Middle core feature showcase cards -->
            <div class="my-auto relative z-10 space-y-6 max-w-sm">
                <div class="space-y-2">
                    <span
                        class="text-xs font-semibold text-emerald-400 tracking-wider uppercase bg-emerald-500/10 px-3 py-1 rounded-full">
                        Premium Platform Features
                    </span>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight font-plus leading-tight">
                        Automated Wealth Reports.
                    </h1>
                </div>

                <!-- Interactive Mini Feature Tiles -->
                <div class="space-y-4">
                    <div class="flex gap-4 p-4 rounded-xl bg-slate-800/25 border border-slate-700/30 backdrop-blur-sm">
                        <div
                            class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                            <i class="fa-solid fa-file-excel"></i>
                        </div>
                        <div>
                            <h3 class="text-white text-sm font-semibold">Instant Excel Exports</h3>
                            <p class="text-slate-400 text-xs mt-0.5 leading-relaxed">Direct integration with PHPOffice
                                for clean monthly spreadsheet generation.</p>
                        </div>
                    </div>

                    <div class="flex gap-4 p-4 rounded-xl bg-slate-800/25 border border-slate-700/30 backdrop-blur-sm">
                        <div
                            class="w-10 h-10 rounded-lg bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-400 shrink-0">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div>
                            <h3 class="text-white text-sm font-semibold">Beautiful Chart Trends</h3>
                            <p class="text-slate-400 text-xs mt-0.5 leading-relaxed">Track income margins and categorize
                                expenses automatically.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom credits footer -->
            <div class="text-xs text-slate-500 relative z-10">
                &copy; 2026 FinStats Global. All rights secured.
            </div>
        </div>

        <!-- RIGHT PANEL: Modern Register Form -->
        <div
            class="col-span-1 lg:col-span-7 flex flex-col justify-center px-6 sm:px-16 lg:px-24 xl:px-32 py-12 relative">
            <div class="max-w-md w-full mx-auto space-y-6">

                <!-- Header metadata -->
                <div class="space-y-1">
                    <h2 class="text-3xl font-bold text-white tracking-tight font-plus">Create Account</h2>
                    <p class="text-slate-400 text-sm">
                        Already registered?
                        <a href="{{ route('login') }}"
                            class="text-emerald-400 hover:text-emerald-300 font-medium transition-colors">Sign in
                            here</a>
                    </p>
                </div>

                <!-- Application Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div class="space-y-1.5">
                        <label for="name"
                            class="block text-xs font-semibold text-slate-300 tracking-wider uppercase">Full
                            Name</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-regular fa-user"></i>
                            </div>
                            <input id="name"
                                class="block w-full pl-10 pr-4 py-2.5 bg-slate-900/60 border border-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-white placeholder-slate-500 rounded-xl transition-all duration-200"
                                type="text" name="name" :value="old('name')" placeholder="John Doe" required
                                autofocus autocomplete="name" />
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

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
                                class="block w-full pl-10 pr-4 py-2.5 bg-slate-900/60 border border-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-white placeholder-slate-500 rounded-xl transition-all duration-200"
                                type="email" name="email" :value="old('email')" placeholder="you@example.com"
                                required autocomplete="username" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <label for="password"
                            class="block text-xs font-semibold text-slate-300 tracking-wider uppercase">Password</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input id="password"
                                class="block w-full pl-10 pr-4 py-2.5 bg-slate-900/60 border border-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-white placeholder-slate-500 rounded-xl transition-all duration-200"
                                type="password" name="password" placeholder="••••••••" required
                                autocomplete="new-password" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-1.5">
                        <label for="password_confirmation"
                            class="block text-xs font-semibold text-slate-300 tracking-wider uppercase">Confirm
                            Password</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-shield"></i>
                            </div>
                            <input id="password_confirmation"
                                class="block w-full pl-10 pr-4 py-2.5 bg-slate-900/60 border border-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-white placeholder-slate-500 rounded-xl transition-all duration-200"
                                type="password" name="password_confirmation" placeholder="••••••••" required
                                autocomplete="new-password" />
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <!-- Terms of agreement check (Visual addition for FinTech feel) -->



                    I accept the secure account encryption terms and allow FinStats to generate local database tables
                    for storage.



                    <!-- Register Button -->
                    <button type="submit"
                        class="w-full mt-4 flex justify-center items-center gap-2 py-3 px-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold rounded-xl shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/35 focus:ring-2 focus:ring-emerald-500/30 transform hover:-translate-y-0.5 transition-all duration-150">
                        <i class="fa-solid fa-user-plus"></i> Complete Account Setup
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-guest-layout>
