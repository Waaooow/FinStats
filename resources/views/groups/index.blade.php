<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-lg sm:text-xl text-slate-100 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-users text-emerald-500"></i>
            {{ __('Tabungan Bersama') }}
        </h2>
    </x-slot>

    <div x-data="{ fabOpen: false, showCreateModal: false, showJoinModal: false }" class="relative min-h-[calc(100vh-8rem)]">
        <div class="py-6 sm:py-8">
            <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">

                @if (session('success'))
                    <div class="bg-[#022c22] border border-emerald-800 text-emerald-400 px-4 py-3 rounded-xl shadow-sm text-xs sm:text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-rose-950/50 border border-rose-900 text-rose-400 px-4 py-3 rounded-xl shadow-sm text-xs sm:text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-200">Daftar Kelompok Tabungan</h3>
                        <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5">Pantau dan kelola tabungan bersama kerabatmu.</p>
                    </div>
                    <div class="hidden sm:flex items-center gap-3">
                        <button @click="showJoinModal = true" class="flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-slate-300 font-semibold text-xs px-4 py-2.5 rounded-xl border border-slate-800 transition">
                            <i class="fa-solid fa-right-to-bracket text-emerald-500"></i> Join Kelompok
                        </button>
                        <button @click="showCreateModal = true" class="flex items-center gap-2 bg-[#10b981] hover:bg-[#059669] text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl transition">
                            <i class="fa-solid fa-plus"></i> Buat Kelompok
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @forelse($groups as $group)
                        <div class="bg-slate-900 rounded-xl border border-slate-800 flex flex-col justify-between group hover:border-emerald-800 transition-colors duration-300">
                            <div class="p-5 sm:p-6">
                                <div class="flex items-start justify-between">
                                    <div class="w-10 h-10 rounded-lg bg-[#022c22] text-emerald-500 flex items-center justify-center text-lg border border-emerald-900/50">
                                        <i class="fa-solid fa-users-viewfinder"></i>
                                    </div>
                                    <span class="bg-slate-950 text-slate-400 font-mono text-[10px] font-bold px-2.5 py-1 rounded-md flex items-center gap-1.5 border border-slate-800">
                                        <i class="fa-solid fa-key text-[9px] text-emerald-500"></i> {{ $group->code }}
                                    </span>
                                </div>

                                <div class="mt-5">
                                    <h4 class="font-bold text-slate-200 text-base group-hover:text-emerald-500 transition-colors">
                                        {{ $group->name }}
                                    </h4>
                                    <div class="flex items-center gap-3 mt-2 text-[11px] text-slate-500 font-medium">
                                        <span class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-user-group text-slate-600"></i>
                                            {{ $group->users->count() }} Anggota
                                        </span>
                                        <span class="text-slate-700">|</span>
                                        <span class="flex items-center gap-1.5 text-emerald-500">
                                            <i class="fa-solid fa-check-circle text-[10px]"></i> Aktif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="px-5 sm:px-6 pb-5 sm:pb-6">
                                <a href="{{ route('groups.show', $group->id) }}" class="w-full inline-flex items-center justify-center gap-2 bg-slate-950/50 text-slate-300 text-xs font-semibold py-2.5 rounded-lg border border-slate-800 hover:bg-[#10b981] hover:text-slate-950 hover:border-[#10b981] transition-all">
                                    <span>Lihat Detail Kelompok</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-1 md:col-span-3 bg-slate-900/50 border border-dashed border-slate-800 rounded-xl p-12 text-center">
                            <div class="w-12 h-12 bg-slate-900 text-slate-600 rounded-xl flex items-center justify-center text-lg mx-auto border border-slate-800 mb-4">
                                <i class="fa-solid fa-folder-open"></i>
                            </div>
                            <p class="font-bold text-slate-300 text-sm">Belum Ada Kelompok</p>
                            <p class="text-xs text-slate-500 mt-1">Buat kelompok baru atau gabung menggunakan kode token.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="fixed bottom-6 right-6 z-40 flex flex-col items-end gap-3 sm:hidden">
            <button x-show="fabOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" @click="showJoinModal = true; fabOpen = false" class="flex items-center gap-2 bg-slate-900 text-slate-200 font-semibold text-xs px-4 py-2.5 rounded-xl border border-slate-800 shadow-xl" style="display: none;">
                <i class="fa-solid fa-right-to-bracket text-emerald-500"></i> Join Kelompok
            </button>
            <button x-show="fabOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" @click="showCreateModal = true; fabOpen = false" class="flex items-center gap-2 bg-slate-900 text-slate-200 font-semibold text-xs px-4 py-2.5 rounded-xl border border-slate-800 shadow-xl" style="display: none;">
                <i class="fa-solid fa-plus text-emerald-500"></i> Buat Kelompok
            </button>
            <button @click="fabOpen = !fabOpen" class="w-12 h-12 bg-[#10b981] text-slate-950 rounded-full flex items-center justify-center text-lg shadow-lg focus:outline-none">
                <i class="fa-solid transition-transform duration-200" :class="fabOpen ? 'fa-xmark rotate-90' : 'fa-plus'"></i>
            </button>
        </div>

        <div x-show="showCreateModal || showJoinModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50" style="display: none;" x-transition></div>

        <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition>
            <div @click.outside="showCreateModal = false" class="bg-slate-900 border border-slate-800 rounded-xl shadow-xl max-w-sm w-full overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-800 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-circle-plus text-emerald-500"></i> Buat Kelompok
                    </h3>
                    <button @click="showCreateModal = false" class="text-slate-500 hover:text-slate-300"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form action="{{ route('groups.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Nama Kelompok</label>
                        <input type="text" name="name" required placeholder="Contoh: Kas RT, Tabungan Liburan" class="w-full rounded-lg bg-slate-950 border-slate-800 text-xs text-slate-200 focus:border-emerald-500 focus:ring-0">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-lg text-xs font-semibold hover:bg-slate-700">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#10b981] text-slate-950 rounded-lg text-xs font-bold hover:bg-[#059669]">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="showJoinModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition>
            <div @click.outside="showJoinModal = false" class="bg-slate-900 border border-slate-800 rounded-xl shadow-xl max-w-sm w-full overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-800 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-right-to-bracket text-emerald-500"></i> Join Kelompok
                    </h3>
                    <button @click="showJoinModal = false" class="text-slate-500 hover:text-slate-300"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form action="{{ route('groups.join') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Kode Token Kelompok</label>
                        <input type="text" name="code" required placeholder="Contoh: KAS-XYZ" class="w-full rounded-lg bg-slate-950 border-slate-800 text-xs text-slate-200 focus:border-emerald-500 focus:ring-0 uppercase tracking-widest">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showJoinModal = false" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-lg text-xs font-semibold hover:bg-slate-700">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-slate-200 text-slate-950 rounded-lg text-xs font-bold hover:bg-white">Gabung Grup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>