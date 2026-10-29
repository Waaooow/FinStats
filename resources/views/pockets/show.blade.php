<x-app-layout>
    <div x-data="{
        openTopUp: false,
        openWithdraw: false,
        openTransfer: false,
        amount: '',
        description: '',
        date: '',
        toType: 'pocket',
        toPocketId: '',
        toGroupId: ''
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 sm:mt-8 space-y-6 pb-12">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <a href="{{ route('pockets.index') }}"
                        class="text-[11px] text-emerald-600 dark:text-emerald-500 font-semibold hover:text-emerald-500 dark:hover:text-emerald-400 inline-flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Daftar Pocket
                    </a>
                    <h2 class="font-bold text-xl sm:text-2xl text-slate-800 dark:text-slate-100 leading-tight mt-1 uppercase tracking-wider">
                        {{ $pocket->name }}
                    </h2>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <button @click="openTopUp = true"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-4 py-2.5 rounded-full text-xs shadow-sm transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus-circle text-sm"></i> Setor
                    </button>
                    <button @click="openWithdraw = true"
                        class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-4 py-2.5 rounded-full text-xs shadow-sm transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-minus-circle text-sm"></i> Tarik
                    </button>
                    <button @click="openTransfer = true"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-4 py-2.5 rounded-full text-xs shadow-sm transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-left text-sm"></i> Transfer
                    </button>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 dark:bg-[#022c22] border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl text-xs sm:text-sm flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400 px-4 py-3 rounded-xl text-xs sm:text-sm flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Info Pocket -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-emerald-600 dark:bg-[#022c22] border border-emerald-500 dark:border-emerald-900/60 rounded-2xl p-6 sm:p-8 relative overflow-hidden shadow-md shadow-emerald-500/10 dark:shadow-none">
                    <div class="relative z-10">
                        <div class="inline-flex items-center gap-2 bg-emerald-700/50 dark:bg-emerald-950/50 border border-emerald-500/50 dark:border-emerald-800/50 rounded-md px-2.5 py-1 mb-4">
                            <i class="fa-solid fa-wallet text-emerald-200 dark:text-emerald-500 text-[10px]"></i>
                            <span class="text-[9px] uppercase font-bold text-emerald-100 dark:text-emerald-400 tracking-widest">SALDO POCKET</span>
                        </div>
                        <p class="text-4xl sm:text-5xl font-black mt-2 tracking-tight text-white">
                            Rp {{ number_format($pocket->balance, 0, ',', '.') }}
                        </p>

                        @if ($pocket->target)
                            <div class="mt-6 pt-4 border-t border-emerald-500/50 dark:border-emerald-800/50">
                                <p class="text-[10px] uppercase font-bold text-emerald-100 dark:text-emerald-400 mb-2">Target</p>
                                <div class="w-full bg-emerald-700/30 rounded-full h-2 mb-1">
                                    <div class="bg-emerald-400 h-2 rounded-full" style="width: {{ min(100, ($pocket->balance / $pocket->target) * 100) }}%"></div>
                                </div>
                                <p class="text-xs text-emerald-200 dark:text-emerald-400">
                                    Rp {{ number_format($pocket->balance, 0, ',', '.') }} / Rp {{ number_format($pocket->target, 0, ',', '.') }}
                                    @if ($pocket->isTargetReached())
                                        <span class="text-emerald-400 font-bold ml-2">✓ Target Tercapai!</span>
                                    @endif
                                </p>
                            </div>
                        @else
                            <p class="mt-6 text-[10px] text-emerald-200 dark:text-emerald-400 italic">Belum ada target yang ditetapkan.</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <button @click="openTopUp = true"
                            class="w-full py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl text-left hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-200 transition flex items-center justify-between">
                            <span>Setor ke Pocket</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </button>
                        <button @click="openWithdraw = true"
                            class="w-full py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl text-left hover:bg-amber-50 dark:hover:bg-amber-900/20 hover:border-amber-200 transition flex items-center justify-between">
                            <span>Tarik dari Pocket</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </button>
                        <button @click="openTransfer = true"
                            class="w-full py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl text-left hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:border-blue-200 transition flex items-center justify-between">
                            <span>Transfer ke Pocket/Grup Lain</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-4">Edit Pocket</h3>
                    <a href="{{ route('pockets.edit', $pocket->id) }}"
                        class="w-full py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl text-left hover:bg-slate-100 dark:hover:bg-slate-800 transition flex items-center justify-between">
                        <span>Ubah Nama / Target</span>
                        <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Riwayat Mutasi Pocket -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm h-[450px] flex flex-col">
                <div class="flex items-center gap-2 shrink-0 border-b border-slate-200 dark:border-slate-800 p-5 sm:p-6">
                    <i class="fa-solid fa-list-ul text-emerald-500 text-sm"></i>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Riwayat Mutasi Pocket</h3>
                </div>

                <div class="flex-1 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-800">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-xs bg-slate-50/50 dark:bg-slate-950/50">
                        <thead class="sticky top-0 bg-slate-100 dark:bg-slate-900 z-10 shadow-sm border-b border-slate-200 dark:border-slate-800">
                            <tr class="text-left font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[10px]">
                                <th class="px-5 py-4">Tanggal</th>
                                <th class="px-5 py-4">Tipe</th>
                                <th class="px-5 py-4 text-right">Jumlah</th>
                                <th class="px-5 py-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-slate-100/50 dark:hover:bg-slate-800/30 transition duration-150">
                                    <td class="px-5 py-4 whitespace-nowrap font-medium text-slate-700 dark:text-slate-300">
                                        {{ \Carbon\Carbon::parse($tx->transaction_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold
                                            @switch($tx->type)
                                                @case('top_up') bg-emerald-100 text-emerald-700 @break
                                                @case('withdraw') bg-rose-100 text-rose-700 @break
                                                @case('transfer_in') bg-blue-100 text-blue-700 @break
                                                @case('transfer_out') bg-orange-100 text-orange-700 @break
                                            @endswitch
                                        ">
                                            {{ $tx->typeLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap font-bold text-right text-sm">
                                        <span class="{{ $tx->type === 'top_up' || $tx->type === 'transfer_in' ? 'text-emerald-600 dark:text-emerald-500' : 'text-rose-600 dark:text-rose-500' }}">
                                            {{ $tx->type === 'top_up' || $tx->type === 'transfer_in' ? '+' : '-' }}
                                            Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-slate-600 dark:text-slate-400 font-medium max-w-xs truncate">
                                        {{ $tx->description }}
                                        @if ($tx->relatedPocket)
                                            <br><span class="text-[10px] text-slate-400 block mt-0.5">→ Pocket: {{ $tx->relatedPocket->name }}</span>
                                        @elseif ($tx->relatedGroup)
                                            <br><span class="text-[10px] text-slate-400 block mt-0.5">→ Grup: {{ $tx->relatedGroup->name }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center text-slate-400 dark:text-slate-500 font-medium">
                                        Belum ada mutasi pocket.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $transactions->links() }}
            </div>

            <!-- Modal Top Up -->
            <div x-show="openTopUp" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/40 dark:bg-slate-950/80 backdrop-blur-sm" style="display: none;" x-transition>
                <div @click.outside="openTopUp = false" class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl max-w-sm w-full overflow-hidden border border-slate-200 dark:border-slate-800 transform transition-all">
                    <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                            <i class="fa-solid fa-money-bill-wave text-emerald-500"></i> Setor ke Pocket
                        </h3>
                        <button @click="openTopUp = false" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <form action="{{ route('pockets.topUp', $pocket->id) }}" method="POST" class="p-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Jumlah Setoran (Rp)</label>
                            <input type="number" name="amount" x-model="amount" min="100" required placeholder="Contoh: 100000"
                                class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Keterangan</label>
                            <input type="text" name="description" x-model="description" placeholder="Contoh: Gaji bulanan"
                                class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Tanggal</label>
                            <input type="date" name="date" x-model="date" value="{{ date('Y-m-d') }}" required
                                class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                        </div>
                        <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-2">
                            <button type="button" @click="openTopUp = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold transition">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white dark:text-slate-950 rounded-xl text-xs font-bold shadow-md transition">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Withdraw -->
            <div x-show="openWithdraw" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/40 dark:bg-slate-950/80 backdrop-blur-sm" style="display: none;" x-transition>
                <div @click.outside="openWithdraw = false" class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl max-w-sm w-full overflow-hidden border border-slate-200 dark:border-slate-800 transform transition-all">
                    <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                            <i class="fa-solid fa-money-bill-wave text-amber-500"></i> Tarik dari Pocket
                        </h3>
                        <button @click="openWithdraw = false" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <form action="{{ route('pockets.withdraw', $pocket->id) }}" method="POST" class="p-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Jumlah Penarikan (Rp)</label>
                            <input type="number" name="amount" x-model="amount" min="100" required placeholder="Contoh: 50000"
                                class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Saldo tersedia: Rp {{ number_format($pocket->balance, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Keterangan</label>
                            <input type="text" name="description" x-model="description" placeholder="Contoh: Belanja bulanan"
                                class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Tanggal</label>
                            <input type="date" name="date" x-model="date" value="{{ date('Y-m-d') }}" required
                                class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                        </div>
                        <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-2">
                            <button type="button" @click="openWithdraw = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold transition">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white dark:text-slate-950 rounded-xl text-xs font-bold shadow-md transition">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Transfer -->
            <div x-show="openTransfer" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/40 dark:bg-slate-950/80 backdrop-blur-sm" style="display: none;" x-transition>
                <div @click.outside="openTransfer = false" class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl max-w-sm w-full overflow-hidden border border-slate-200 dark:border-slate-800 transform transition-all">
                    <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                            <i class="fa-solid fa-right-left text-blue-500"></i> Transfer Pocket
                        </h3>
                        <button @click="openTransfer = false" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <form action="{{ route('pockets.transfer') }}" method="POST" class="p-5 space-y-4">
                        @csrf
                        <input type="hidden" name="from_pocket_id" value="{{ $pocket->id }}">

                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Jumlah Transfer (Rp)</label>
                            <input type="number" name="amount" x-model="amount" min="100" required placeholder="Contoh: 200000"
                                class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Transfer ke</label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="to_type" value="pocket" x-model="toType" checked class="text-emerald-600 focus:ring-emerald-500">
                                    <span class="text-xs text-slate-700 dark:text-slate-300">Pocket Lain (Milik Saya)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="to_type" value="group" x-model="toType" class="text-emerald-600 focus:ring-emerald-500">
                                    <span class="text-xs text-slate-700 dark:text-slate-300">Tabungan Bersama (Grup)</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="toType === 'pocket'" x-transition>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Pilih Pocket Tujuan</label>
                            <select name="to_pocket_id" x-model="toPocketId" class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                                @foreach(Auth::user()->pockets()->where('id', '!=', $pocket->id)->get() as $otherPocket)
                                    <option value="{{ $otherPocket->id }}">{{ $otherPocket->name }} (Rp {{ number_format($otherPocket->balance, 0, ',', '.') }})</option>
                                @endforeach
                                @if (Auth::user()->pockets()->where('id', '!=', $pocket->id)->count() === 0)
                                    <option disabled>Tidak ada pocket lain</option>
                                @endif
                            </select>
                        </div>

                        <div x-show="toType === 'group'" x-transition>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Pilih Tabungan Bersama</label>
                            <select name="to_group_id" x-model="toGroupId" class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                                @foreach(Auth::user()->groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->code }})</option>
                                @endforeach
                                @if (Auth::user()->groups->count() === 0)
                                    <option disabled>Tidak tergabung dalam kelompok manapun</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Keterangan</label>
                            <input type="text" name="description" x-model="description" placeholder="Contoh: Pindahkan ke tabungan liburan"
                                class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Tanggal</label>
                            <input type="date" name="date" x-model="date" value="{{ date('Y-m-d') }}" required
                                class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                        </div>
                        <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-2">
                            <button type="button" @click="openTransfer = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold transition">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white dark:text-slate-950 rounded-xl text-xs font-bold shadow-md transition">Transfer</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
