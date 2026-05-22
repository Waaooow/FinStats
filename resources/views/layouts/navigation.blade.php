<nav x-data="{ open: false }"
    class="bg-white border-b border-gray-100 shadow-xs sticky top-0 z-40 backdrop-blur-md bg-white/95">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <div
                            class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-500 text-white flex items-center justify-center shadow-md shadow-indigo-200 group-hover:scale-105 transition-all duration-200">
                            <i class="fa-solid fa-chart-simple text-sm"></i>
                        </div>
                        <span
                            class="font-black text-base tracking-tight bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Fin<span class="text-gray-800 font-semibold text-sm tracking-normal">Stats</span>
                        </span>
                    </a>
                </div>

                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-3 pt-1 border-b-2 text-xs font-bold leading-5 transition duration-150 ease-in-out {{ request()->routeIs('dashboard') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-indigo-500 hover:border-gray-300' }}">
                        <i class="fa-solid fa-gauge-high mr-1.5 text-[11px]"></i> Dashboard
                    </a>

                    <a href="{{ route('groups.index') }}"
                        class="inline-flex items-center px-3 pt-1 border-b-2 text-xs font-bold leading-5 transition duration-150 ease-in-out {{ request()->routeIs('groups.*') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-indigo-500 hover:border-gray-300' }}">
                        <i class="fa-solid fa-users mr-1.5 text-[11px]"></i> Tabungan Bersama
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-gray-100 text-xs font-bold rounded-xl text-gray-600 bg-gray-50/50 hover:text-indigo-600 hover:bg-indigo-50/50 hover:border-indigo-100 transition duration-150 ease-in-out">
                            <div
                                class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center mr-1.5 font-bold text-[10px]">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <i class="fa-solid fa-chevron-down text-[9px] opacity-70"></i>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')"
                            class="text-xs font-semibold text-gray-700 flex items-center gap-2">
                            <i class="fa-solid fa-user-gear text-gray-400 text-[11px]"></i>
                            {{ __('Pengaturan Profil') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-xs font-semibold text-rose-600 hover:bg-rose-50 flex items-center gap-2">
                                <i class="fa-solid fa-right-from-bracket text-rose-400 text-[11px]"></i>
                                {{ __('Keluar Aplikasi') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-xl text-gray-400 hover:text-indigo-500 hover:bg-indigo-50 transition duration-150 ease-in-out">
                    <i :class="{ 'hidden': open, 'inline-flex': !open }" class="fa-solid fa-bars text-sm"></i>
                    <i :class="{ 'hidden': !open, 'inline-flex': open }" class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-gray-50 border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-gauge-high text-sm"></i> Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('groups.index')" :active="request()->routeIs('groups.*')"
                class="text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-users text-sm"></i> Tabungan Bersama
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 flex items-center gap-2">
                <div
                    class="w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-bold text-xs text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-[11px] text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-xs font-semibold flex items-center gap-2">
                    <i class="fa-solid fa-user-gear text-gray-400"></i> Pengaturan Profil
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="text-xs font-bold text-rose-600 flex items-center gap-2">
                        <i class="fa-solid fa-right-from-bracket text-rose-400"></i> Keluar Aplikasi
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
