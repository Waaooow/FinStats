<x-app-layout>
    <x-slot name="header">
        <h2
            class="font-bold text-lg sm:text-xl text-slate-800 dark:text-slate-100 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-users text-emerald-500"></i>
            {{ __('Tabungan Bersama') }}
        </h2>
    </x-slot>

    <div x-data="{ fabOpen: false, showCreateModal: false, showJoinModal: false }" class="relative min-h-[calc(100vh-8rem)]">
        <div class="py-6 sm:py-8">
            <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">

                @if (session('success'))
                    <div
                        class="bg-emerald-50 dark:bg-[#022c22] border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl shadow-sm text-xs sm:text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div
                        class="bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400 px-4 py-3 rounded-xl shadow-sm text-xs sm:text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-800 dark:text-slate-200">Daftar Kelompok
                            Tabungan</h3>
                        <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pantau dan kelola
                            tabungan bersama kerabatmu.</p>
                    </div>
                    <div class="hidden sm:flex items-center gap-3">
                        <button @click="showJoinModal = true"
                            class="flex items-center gap-2 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition">
                            <i class="fa-solid fa-right-to-bracket text-emerald-500"></i> Join Kelompok
                        </button>
                        <button @click="showCreateModal = true"
                            class="flex items-center gap-2 bg-[#10b981] hover:bg-[#059669] text-white dark:text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                            <i class="fa-solid fa-plus"></i> Buat Kelompok
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @forelse($groups as $group)
                        <div
                            class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 flex flex-col justify-between group hover:border-emerald-500 dark:hover:border-emerald-800 transition-colors duration-300 shadow-sm">
                            <div class="p-5 sm:p-6">
                                <div class="flex items-start justify-between">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-[#022c22] text-emerald-600 dark:text-emerald-500 flex items-center justify-center text-lg border border-emerald-200 dark:border-emerald-900/50">
                                        <i class="fa-solid fa-users-viewfinder"></i>
                                    </div>
                                    <span
                                        class="bg-slate-100 dark:bg-slate-950 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 font-mono text-[10px] font-bold px-2.5 py-1 rounded-md flex items-center">
                                        {{ $group->code }}
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <h4 class="font-bold text-base text-slate-800 dark:text-slate-100">
                                        {{ $group->name }}</h4>
                                    <p
                                        class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5">
                                        <i class="fa-solid fa-user-group text-[10px]"></i> {{ $group->users->count() }}
                                        Anggota
                                    </p>
                                </div>
                            </div>
                            <div
                                class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-transparent rounded-b-xl">
                                <a href="{{ route('groups.show', $group->id) }}"
                                    class="w-full flex items-center justify-center gap-2 bg-white dark:bg-slate-950 text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 border border-slate-200 dark:border-slate-800 hover:border-emerald-500/50 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 transition-all text-xs font-bold py-2 rounded-lg">
                                    Lihat Detail <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-1 md:col-span-3 bg-slate-50 dark:bg-slate-900/50 border border-dashed border-slate-300 dark:border-slate-800 rounded-2xl p-10 flex flex-col items-center justify-center text-center">
                            <div
                                class="w-16 h-16 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-400 dark:text-slate-500 mb-4 text-2xl">
                                <i class="fa-solid fa-users-slash"></i>
                            </div>
                            <h3 class="text-slate-700 dark:text-slate-300 font-bold mb-1">Belum Ada Kelompok</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm">Kamu belum bergabung dengan
                                kelompok tabungan manapun. Silakan buat baru atau join menggunakan kode token.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="fixed bottom-6 right-6 sm:hidden z-40 flex flex-col items-end gap-3">
            <div x-show="fabOpen" x-transition class="flex flex-col gap-3 items-end">
                <button @click="showJoinModal = true; fabOpen = false"
                    class="flex items-center gap-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 px-4 py-2.5 rounded-full shadow-lg border border-slate-200 dark:border-slate-700 text-xs font-bold transition">
                    Join Kelompok <i class="fa-solid fa-right-to-bracket text-emerald-500"></i>
                </button>
                <button @click="showCreateModal = true; fabOpen = false"
                    class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-full shadow-lg text-xs font-bold transition">
                    Buat Kelompok <i class="fa-solid fa-plus"></i>
                </button>
            </div>
            <button @click="fabOpen = !fabOpen"
                class="w-14 h-14 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full shadow-lg shadow-emerald-500/30 flex items-center justify-center text-xl transition-transform duration-200"
                :class="{ 'rotate-45': fabOpen }">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>

        <div x-show="showCreateModal || showJoinModal"
            class="fixed inset-0 bg-slate-900/40 dark:bg-slate-950/80 backdrop-blur-sm z-50" style="display: none;"
            x-transition></div>

        <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="display: none;" x-transition>
            <div @click.outside="showCreateModal = false"
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl max-w-sm w-full overflow-hidden transform transition-all">
                <div
                    class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-plus-circle text-emerald-500"></i> Buat Kelompok Baru
                    </h3>
                    <button @click="showCreateModal = false"
                        class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <form action="{{ route('groups.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Nama
                            Kelompok</label>
                        <input type="text" name="name" required placeholder="Contoh: Tabungan Liburan Bali"
                            class="w-full rounded-xl bg-white dark:bg-slate-950 border-slate-300 dark:border-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0 placeholder-slate-400 dark:placeholder-slate-700">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showCreateModal = false"
                            class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white dark:text-slate-950 rounded-xl text-xs font-bold transition">Buat
                            Kelompok</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="showJoinModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="display: none;" x-transition>
            <div @click.outside="showJoinModal = false"
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl max-w-sm w-full overflow-hidden transform transition-all">
                <div
                    class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-right-to-bracket text-emerald-500"></i> Join Kelompok
                    </h3>
                    <button @click="showJoinModal = false"
                        class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <form action="{{ route('groups.join') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Kode Token
                            Kelompok</label>
                        <input type="text" name="code" required placeholder="Contoh: KAS-XYZ"
                            class="w-full rounded-xl bg-white dark:bg-slate-950 border-slate-300 dark:border-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0 uppercase tracking-widest placeholder-slate-400 dark:placeholder-slate-700">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showJoinModal = false"
                            class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white dark:text-slate-950 rounded-xl text-xs font-bold transition">Join
                            Kelompok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
