<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-lg sm:text-xl text-gray-800 leading-tight flex items-center gap-2 px-2 sm:px-0">
            <i class="fa-solid fa-users text-indigo-600"></i>
            {{ __('Tabungan Bersama') }}
        </h2>
    </x-slot>

    <div x-data="{
        fabOpen: false,
        showCreateModal: false,
        showJoinModal: false
    }" class="relative min-h-[calc(100vh-4rem)]">

        <div class="py-6 sm:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 space-y-5 sm:space-y-6">

                @if (session('success'))
                    <div
                        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl shadow-sm text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-green-500"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div
                        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl shadow-sm text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm sm:text-lg font-bold text-gray-800">Kelompok Tabungan Kamu</h3>
                        <p class="text-[10px] sm:text-xs text-gray-500">Daftar tempat kamu menabung bersama kelompok
                            atau keluarga</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                        @forelse($groups as $group)
                            <div
                                class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-indigo-100 transition-all duration-300 overflow-hidden flex flex-col justify-between group">
                                <div class="p-5 sm:p-6">
                                    <div class="flex items-start justify-between">
                                        <div
                                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-blue-600 via-indigo-600 to-indigo-700 text-white flex items-center justify-center text-lg sm:text-xl shadow-md group-hover:scale-105 transition duration-300">
                                            <i class="fa-solid fa-users-viewfinder"></i>
                                        </div>
                                        <span
                                            class="bg-indigo-50 text-indigo-700 font-mono text-[10px] font-bold px-2.5 py-1 rounded-lg flex items-center gap-1 border border-indigo-100/50">
                                            <i class="fa-solid fa-key text-[9px]"></i> {{ $group->code }}
                                        </span>
                                    </div>

                                    <div class="mt-4">
                                        <h4
                                            class="font-bold text-gray-800 text-base sm:text-lggroup-hover:text-indigo-600 transition duration-200">
                                            {{ $group->name }}</h4>
                                        <div
                                            class="flex items-center gap-3 mt-2 sm:mt-3 text-[10px] sm:text-xs text-gray-500 font-medium">
                                            <span class="flex items-center gap-1">
                                                <i class="fa-solid fa-user-group text-gray-400"></i>
                                                {{ $group->users->count() }} Anggota
                                            </span>
                                            <span class="text-gray-300">|</span>
                                            <span class="flex items-center gap-1 text-emerald-600 font-semibold">
                                                <i class="fa-solid fa-chart-line"></i> Terpantau
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-5 pb-5 sm:px-6 sm:pb-6 pt-1">
                                    <a href="{{ route('groups.show', $group->id) }}"
                                        class="w-full inline-flex items-center justify-center gap-1.5 bg-gray-100 text-gray-700 text-[10px] sm:text-xs font-semibold py-2 sm:py-2.5 rounded-lg sm:rounded-xl hover:bg-indigo-600 hover:text-white transition shadow-sm">
                                        <span>Buka Detail Dana</span>
                                        <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div
                                class="col-span-1 md:col-span-3 bg-white border border-dashed border-gray-200 rounded-xl sm:rounded-2xl p-8 sm:p-12 text-center space-y-3">
                                <div
                                    class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center text-xl sm:text-2xl mx-auto">
                                    <i class="fa-solid fa-folder-open"></i>
                                </div>
                                <div class="max-w-sm mx-auto">
                                    <p class="font-bold text-gray-700 text-xs sm:text-sm">Belum Ada Kelompok</p>
                                    <p class="text-[10px] sm:text-xs text-gray-400 mt-1">Klik tombol bulat di pojok
                                        layar untuk membuat kelompok baru atau bergabung dengan kode token kelompok
                                        lain.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        <div class="fixed bottom-6 right-6 z-40 flex flex-col items-end gap-3">

            <button x-show="fabOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                @click="showJoinModal = true; fabOpen = false"
                class="flex items-center gap-2 bg-white text-gray-700 font-bold text-xs px-4 py-2.5 rounded-xl shadow-lg hover:bg-gray-50 border border-gray-100 transition"
                style="display: none;">
                <i class="fa-solid fa-right-to-bracket text-indigo-600"></i>
                <span>Join Kelompok</span>
            </button>

            <button x-show="fabOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                @click="showCreateModal = true; fabOpen = false"
                class="flex items-center gap-2 bg-white text-gray-700 font-bold text-xs px-4 py-2.5 rounded-xl shadow-lg hover:bg-gray-50 border border-gray-100 transition"
                style="display: none;">
                <i class="fa-solid fa-plus text-emerald-500"></i>
                <span>Buat Kelompok</span>
            </button>

            <button @click="fabOpen = !fabOpen"
                class="w-14 h-14 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-full flex items-center justify-center text-xl shadow-xl hover:scale-105 active:scale-95 transition-all duration-200 focus:outline-none">
                <i class="fa-solid transition-transform duration-200"
                    :class="fabOpen ? 'fa-xmark rotate-90' : 'fa-plus'"></i>
            </button>
        </div>

        <div x-show="showCreateModal || showJoinModal"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 transition-opacity" style="display: none;"></div>

        <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
            style="display: none;">
            <div @click.outside="showCreateModal = false"
                class="bg-white rounded-xl sm:rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-gray-100">
                <div class="px-5 py-3.5 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-circle-plus text-emerald-500"></i> Buat Kelompok Baru
                    </h3>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <form action="{{ route('groups.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kelompok Tabungan</label>
                        <input type="text" name="name" required placeholder="Contoh: Kas RT, Tabungan Berdua"
                            class="w-full rounded-xl border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="flex justify-end gap-2.5 pt-2">
                        <button type="button" @click="showCreateModal = false"
                            class="px-3 py-1.5 border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-xl shadow-md hover:bg-indigo-700 transition">Simpan
                            Kelompok</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="showJoinModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
            style="display: none;">
            <div @click.outside="showJoinModal = false"
                class="bg-white rounded-xl sm:rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-gray-100">
                <div class="px-5 py-3.5 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-right-to-bracket text-indigo-600"></i> Gabung Kelompok
                    </h3>
                    <button @click="showJoinModal = false" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <form action="{{ route('groups.join') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Token Kelompok</label>
                        <input type="text" name="code" required placeholder="Contoh: KAS-XYZ123"
                            class="w-full rounded-xl border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 uppercase">
                        <p class="text-[10px] text-gray-400 mt-1">Minta kode token unik 6-digit ke pemilik kelompok.
                        </p>
                    </div>
                    <div class="flex justify-end gap-2.5 pt-2">
                        <button type="button" @click="showJoinModal = false"
                            class="px-3 py-1.5 border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-1.5 bg-gray-800 text-white text-xs font-semibold rounded-xl shadow-md hover:bg-gray-900 transition">Gabung
                            Grup</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
