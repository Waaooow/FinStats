<nav x-data="{ open: false }"
    class="border-b border-slate-200 dark:border-slate-900 bg-white/70 dark:bg-slate-900/40 backdrop-blur-md sticky top-0 z-40 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">

            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <div
                            class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-all duration-200">
                            <i class="fa-solid fa-chart-pie text-white text-sm"></i>
                        </div>
                        <span
                            class="font-black text-lg tracking-tight text-slate-900 dark:text-white transition-colors duration-300">
                            Fin<span class="text-emerald-500 dark:text-emerald-400 font-semibold text-sm">Stats</span>
                        </span>
                    </a>
                </div>

                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 text-xs font-bold transition duration-150 ease-in-out {{ request()->routeIs('dashboard') ? 'border-emerald-500 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:border-slate-300 dark:hover:border-slate-800' }}">
                        <i class="fa-solid fa-gauge-high mr-1.5 text-[11px]"></i> Dashboard
                    </a>

                    <a href="{{ route('groups.index') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 text-xs font-bold transition duration-150 ease-in-out {{ request()->routeIs('groups.*') ? 'border-emerald-500 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:border-slate-300 dark:hover:border-slate-800' }}">
                        <i class="fa-solid fa-users mr-1.5 text-[11px]"></i> Tabungan Bersama
                    </a>

                    <a href="{{ route('pockets.index') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 text-xs font-bold transition duration-150 ease-in-out {{ request()->routeIs('pockets.*') ? 'border-emerald-500 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:border-slate-300 dark:hover:border-slate-800' }}">
                        <i class="fa-solid fa-wallet mr-1.5 text-[11px]"></i> Pocket
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

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
                    class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition duration-200"
                    title="Ubah Tema">
                    <i class="fa-solid fa-sun text-amber-500" x-show="theme === 'dark'" style="display: none;"></i>
                    <i class="fa-solid fa-moon text-indigo-500" x-show="theme !== 'dark'" style="display: none;"></i>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-slate-200 dark:border-slate-800 text-xs font-bold rounded-xl text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-900/60 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-900 transition duration-150 ease-in-out">
                            <div
                                class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mr-1.5 font-bold text-[10px] border border-emerald-200 dark:border-emerald-500/20">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <i class="fa-solid fa-chevron-down text-[9px] opacity-70"></i>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div
                            class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-md shadow-xl py-1 transition-colors duration-300">
                            <a href="{{ route('profile.edit') }}"
                                class="block w-full px-4 py-2 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition flex items-center gap-2">
                                <i class="fa-solid fa-user-gear text-slate-500 text-[11px]"></i>
                                {{ __('Pengaturan Profil') }}
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="block w-full px-4 py-2 text-left text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/50 hover:text-rose-700 dark:hover:text-rose-300 transition flex items-center gap-2">
                                    <i class="fa-solid fa-right-from-bracket text-rose-500 text-[11px]"></i>
                                    {{ __('Keluar Aplikasi') }}
                                </a>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden gap-2">
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
                    class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <i class="fa-solid fa-sun text-amber-500" x-show="theme === 'dark'" style="display: none;"></i>
                    <i class="fa-solid fa-moon text-indigo-500" x-show="theme !== 'dark'" style="display: none;"></i>
                </button>

                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-emerald-500 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-900 transition duration-150 ease-in-out">
                    <i :class="{ 'hidden': open, 'inline-flex': !open }" class="fa-solid fa-bars text-sm"></i>
                    <i :class="{ 'hidden': !open, 'inline-flex': open }" class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }"
        class="hidden sm:hidden bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 transition-colors duration-300">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}"
                class="block w-full pl-3 pr-4 py-2 border-l-4 text-left text-xs font-bold transition duration-150 ease-in-out flex items-center gap-2 {{ request()->routeIs('dashboard') ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600' }}">
                <i class="fa-solid fa-gauge-high text-sm"></i> Dashboard
            </a>
            <a href="{{ route('groups.index') }}"
                class="block w-full pl-3 pr-4 py-2 border-l-4 text-left text-xs font-bold transition duration-150 ease-in-out flex items-center gap-2 {{ request()->routeIs('groups.*') ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600' }}">
                <i class="fa-solid fa-users text-sm"></i> Tabungan Bersama
            </a>

            <a href="{{ route('pockets.index') }}"
                class="block w-full pl-3 pr-4 py-2 border-l-4 text-left text-xs font-bold transition duration-150 ease-in-out flex items-center gap-2 {{ request()->routeIs('pockets.*') ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600' }}">
                <i class="fa-solid fa-wallet text-sm"></i> Pocket
            </a>
        </div>

        <div class="pt-4 pb-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40">
            <div class="px-4 flex items-center gap-2">
                <div
                    class="w-8 h-8 rounded-full bg-emerald-500 text-white dark:text-slate-950 flex items-center justify-center text-xs font-black">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-[11px] text-slate-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}"
                    class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 transition flex items-center gap-2">
                    <i class="fa-solid fa-user-gear text-slate-500 dark:text-slate-600"></i> Pengaturan Profil
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                        class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-xs font-bold text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition flex items-center gap-2">
                        <i class="fa-solid fa-right-from-bracket text-rose-500"></i> Keluar Aplikasi
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
