<x-guest-layout>
    <div class="min-h-screen relative overflow-hidden font-sans select-none">

        <div
            class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] rounded-full bg-emerald-500/20 dark:bg-emerald-500/10 blur-[130px] pointer-events-none animate-pulse">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] rounded-full bg-teal-500/20 dark:bg-teal-500/10 blur-[130px] pointer-events-none">
        </div>

        <nav
            class="border-b border-slate-200 dark:border-slate-900 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl sticky top-0 z-50 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <i class="fa-solid fa-chart-pie text-white text-lg"></i>
                    </div>
                    <span
                        class="font-bold text-2xl text-slate-900 dark:text-white tracking-tight font-plus transition-colors">
                        Fin<span class="text-emerald-500 dark:text-emerald-400">Stats</span>
                    </span>
                </div>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#features"
                        class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Features</a>
                    <a href="#stats"
                        class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Analytics</a>
                    <a href="#security"
                        class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Security</a>
                </div>

                <div class="flex items-center gap-4">
                    <button x-data="{ theme: localStorage.theme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') }"
                        @click="
                            theme = theme === 'dark' ? 'light' : 'dark';
                            localStorage.theme = theme;
                            if (theme === 'dark') {
                                document.documentElement.classList.add('dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                            }
                        "
                        class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition duration-200"
                        title="Ubah Tema">
                        <i class="fa-solid fa-sun text-amber-500" x-show="theme === 'dark'" style="display: none;"></i>
                        <i class="fa-solid fa-moon text-indigo-500" x-show="theme !== 'dark'"
                            style="display: none;"></i>
                    </button>

                    <a href="{{ route('login') }}"
                        class="text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-white transition-colors hidden sm:block">Sign
                        In</a>
                    <a href="{{ route('register') }}"
                        class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-sm font-bold rounded-xl text-white shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5">
                        Get Started Free
                    </a>
                </div>
            </div>
        </nav>

        <header class="max-w-7xl mx-auto px-6 lg:px-8 pt-16 lg:pt-24 pb-20 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <div class="lg:col-span-7 space-y-6 text-left">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-semibold uppercase tracking-wider">
                        <i class="fa-solid fa-bolt-lightning"></i> Intelligent Budgeting Platform
                    </div>

                    <h1
                        class="text-4xl sm:text-6xl font-black text-slate-900 dark:text-white tracking-tight leading-none font-plus transition-colors">
                        Smart Budgeting. <br>
                        <span
                            class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 dark:from-emerald-400 dark:via-teal-400 dark:to-cyan-400 bg-clip-text text-transparent">Real-Time
                            Precision.</span>
                    </h1>

                    <p
                        class="text-base sm:text-lg text-slate-600 dark:text-slate-400 max-w-xl leading-relaxed transition-colors">
                        Stop guessing where your money goes. FinStats aggregates your financial pipelines, monitors cash
                        flows, and auto-calculates assets inside a secure, seamless dashboard.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center gap-4 pt-4">
                        <a href="{{ route('login') }}"
                            class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold rounded-xl shadow-xl shadow-emerald-500/20 hover:shadow-emerald-500/35 transition-all text-center group">
                            Launch Your Dashboard <i
                                class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="#features"
                            class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-semibold rounded-xl transition-all text-center">
                            Learn More
                        </a>
                    </div>

                    <div
                        class="pt-8 border-t border-slate-200 dark:border-slate-900 grid grid-cols-3 gap-4 text-left transition-colors">
                        <div>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white font-plus">99.9%</p>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mt-0.5">Uptime Rate</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white font-plus">Secure</p>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mt-0.5">Data Encryption</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white font-plus">Instant</p>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mt-0.5">Exchange Sync</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 relative">
                    <div
                        class="absolute inset-0 bg-gradient-to-tr from-emerald-500/20 dark:from-emerald-500/10 to-transparent rounded-3xl filter blur-xl opacity-70">
                    </div>

                    <div
                        class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-2xl relative space-y-6 transition-colors">
                        <div
                            class="flex justify-between items-center border-b border-slate-200 dark:border-slate-800/80 pb-4">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-rose-500/70 block"></span>
                                <span class="w-3 h-3 rounded-full bg-amber-500/70 block"></span>
                                <span class="w-3 h-3 rounded-full bg-emerald-500/70 block"></span>
                            </div>
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-mono">finstats_overview</span>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400">
                                <span>Total Portfolio Value</span>
                                <span class="text-emerald-500 dark:text-emerald-400 font-semibold">+14.2% YoY</span>
                            </div>
                            <div class="text-3xl font-black text-slate-900 dark:text-white tracking-tight font-plus">
                                $382,490.50</div>
                        </div>

                        <div class="h-24 flex items-end gap-1.5 pt-4">
                            <div
                                class="bg-slate-200 dark:bg-slate-800 w-full h-[30%] rounded-md transition-all duration-500 hover:h-[50%]">
                            </div>
                            <div class="bg-slate-200 dark:bg-slate-800 w-full h-[45%] rounded-md"></div>
                            <div class="bg-slate-200 dark:bg-slate-800 w-full h-[25%] rounded-md"></div>
                            <div class="bg-slate-200 dark:bg-slate-800 w-full h-[60%] rounded-md"></div>
                            <div
                                class="bg-gradient-to-t from-emerald-500 to-teal-400 dark:from-emerald-600 dark:to-teal-400 w-full h-[85%] rounded-md shadow-lg shadow-emerald-500/20">
                            </div>
                        </div>

                        <div
                            class="bg-slate-50/80 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800/60 rounded-xl p-3 flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <i class="fa-solid fa-shield-check text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-slate-800 dark:text-slate-200">Automated Audit
                                    Concluded</p>
                                <p class="text-[10px] text-slate-500">Zero budget discrepancies found.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <section id="features"
            class="max-w-7xl mx-auto px-6 lg:px-8 py-20 border-t border-slate-200 dark:border-slate-900 relative z-10 transition-colors">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight font-plus">
                    Architected for High-Fidelity Tracking</h2>
                <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base">Everything you need to map out your
                    digital wealth without dealing with archaic spreadsheets.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="bg-white/40 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/60 hover:border-emerald-500/50 dark:hover:border-emerald-500/30 p-6 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none transition-all group">
                    <div
                        class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-emerald-500 dark:text-emerald-400 text-lg mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 font-plus">Pipeline Automation</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">Streamline recurring
                        transaction records seamlessly. Let backend systems auto-populate ledger inputs effortlessly.
                    </p>
                </div>

                <div
                    class="bg-white/40 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/60 hover:border-emerald-500/50 dark:hover:border-emerald-500/30 p-6 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none transition-all group">
                    <div
                        class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-emerald-500 dark:text-emerald-400 text-lg mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 font-plus">Live Exchange Matrix
                    </h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">Dynamic calculations pull
                        global currency data endpoints to keep your balance values synced accurately.</p>
                </div>

                <div
                    class="bg-white/40 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/60 hover:border-emerald-500/50 dark:hover:border-emerald-500/30 p-6 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none transition-all group">
                    <div
                        class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-emerald-500 dark:text-emerald-400 text-lg mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-lock-keyhole"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 font-plus">Data Privacy</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">Built with secure application
                        state tokens and customized access protection to keep your personal data secure.</p>
                </div>
            </div>
        </section>

        <footer
            class="border-t border-slate-200 dark:border-slate-900 bg-slate-50 dark:bg-slate-950 py-8 relative z-10 transition-colors">
            <div
                class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; 2026 FinStats Global. All data rights protected under secure node architectures.</p>
                <div class="flex gap-6">
                    <a href="#"
                        class="hover:text-slate-800 dark:hover:text-slate-300 transition-colors">Documentation</a>
                    <a href="#" class="hover:text-slate-800 dark:hover:text-slate-300 transition-colors">Privacy
                        Policy</a>
                    <a href="#" class="hover:text-slate-800 dark:hover:text-slate-300 transition-colors">Terms
                        of Service</a>
                </div>
            </div>
        </footer>

    </div>
</x-guest-layout>
