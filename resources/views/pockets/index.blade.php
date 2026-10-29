<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-lg sm:text-xl text-slate-800 dark:text-slate-100 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-wallet text-emerald-500"></i>
            {{ __('My Pocket') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-emerald-50 dark:bg-[#022c22] border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl shadow-sm text-xs sm:text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400 px-4 py-3 rounded-xl shadow-sm text-xs sm:text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Ringkasan Saldo -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-800 dark:text-slate-200">Total Saldo Pocket</h3>
                        <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5">Akumulasi seluruh pocket milik kamu</p>
                    </div>
                    <a href="{{ route('pockets.create') }}"
                        class="flex items-center gap-2 bg-[#10b981] hover:bg-[#059669] text-white dark:text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                        <i class="fa-solid fa-plus"></i> Buat Pocket Baru
                    </a>
                </div>

                <div class="text-4xl sm:text-5xl font-black text-slate-800 dark:text-white tracking-tight">
                    Rp {{ number_format($totalBalance ?? 0, 0, ',', '.') }}
                </div>

                @if ($totalTarget && $totalTarget > 0)
                    <div class="mt-4">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Target Keseluruhan</p>
                        <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ min(100, ($totalBalance / $totalTarget) * 100) }}%"></div>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Rp {{ number_format($totalBalance, 0, ',', '.') }} / Rp {{ number_format($totalTarget, 0, ',', '.') }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Daftar Pocket -->
            @if ($pockets->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($pockets as $pocket)
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 flex flex-col justify-between group hover:border-emerald-500 dark:hover:border-emerald-800 transition-colors duration-300 shadow-sm">
                            <div class="p-5 sm:p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h4 class="font-bold text-base text-slate-800 dark:text-slate-100 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                            {{ $pocket->name }}
                                        </h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5">
                                            <i class="fa-solid fa-piggy-bank text-[10px]"></i>
                                            Saldo: Rp {{ number_format($pocket->balance, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <span
                                        class="bg-slate-100 dark:bg-slate-950 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 font-mono text-[10px] font-bold px-2.5 py-1 rounded-md flex items-center">
                                        #{{ $pocket->id }}
                                    </span>
                                </div>

                                @if ($pocket->target)
                                    <div class="mb-4">
                                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mb-1">Target</p>
                                        <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-1.5 mb-1">
                                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ min(100, ($pocket->balance / $pocket->target) * 100) }}%"></div>
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            Rp {{ number_format($pocket->balance, 0, ',', '.') }} / Rp {{ number_format($pocket->target, 0, ',', '.') }}
                                            @if ($pocket->isTargetReached())
                                                <span class="text-emerald-500 font-bold ml-1">✓ Tercapai</span>
                                            @endif
                                        </p>
                                    </div>
                                @else
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mb-3">Belum ada target</p>
                                @endif

                                <div class="flex gap-2">
                                    <a href="{{ route('pockets.show', $pocket->id) }}"
                                        class="flex-1 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl text-center hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                        Lihat Detail
                                    </a>
                                    <form action="{{ route('pockets.destroy', $pocket->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pocket ini? Saldo harus 0 dan tidak ada mutasi.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-2 py-2 bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-600 dark:text-rose-400 text-xs font-bold rounded-xl hover:bg-rose-100 dark:hover:bg-rose-900/30 transition" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-slate-50 dark:bg-slate-900/50 border border-dashed border-slate-300 dark:border-slate-800 rounded-2xl p-12 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-400 dark:text-slate-500 mb-4 text-3xl">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <h3 class="text-slate-700 dark:text-slate-300 font-bold mb-1">Belum Ada Pocket</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mb-6">Kamu belum memiliki pocket tabungan. Buat pocket pertama untuk mulai menabung secara terstruktur.</p>
                    <a href="{{ route('pockets.create') }}"
                        class="bg-[#10b981] hover:bg-[#059669] text-white dark:text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                        Buat Pocket Baru
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
