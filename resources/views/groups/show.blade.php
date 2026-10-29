<x-app-layout>
    <div x-data="{
        open: false,
        amount: '',
        editOpen: false,
        editId: '',
        editType: 'income',
        editAmount: '',
        editDesc: '',
        editDate: ''
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 sm:mt-8 space-y-6 pb-12">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <a href="{{ route('groups.index') }}"
                        class="text-[11px] text-emerald-600 dark:text-emerald-500 font-semibold hover:text-emerald-500 dark:hover:text-emerald-400 inline-flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke List
                    </a>
                    <h2
                        class="font-bold text-xl sm:text-2xl text-slate-800 dark:text-slate-100 leading-tight mt-1 uppercase tracking-wider">
                        {{ $group->name }}
                    </h2>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <a href="{{ route('transactions.export', ['group_id' => $group->id]) }}"
                        class="inline-flex items-center justify-center gap-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition px-3 py-1.5 rounded-lg text-[10px] font-bold self-start sm:self-center shrink-0">
                        <i class="fa-solid fa-file-excel text-emerald-500"></i> Ekspor (.xlsx)
                    </a>
                    <button @click="open = true"
                        class="bg-[#10b981] hover:bg-[#059669] text-white dark:text-slate-950 font-bold px-4 py-2.5 rounded-full text-xs shadow-sm transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus-circle text-sm"></i> Catat Transaksi
                    </button>
                </div>
            </div>

            @if (session('success'))
                <div
                    class="bg-emerald-50 dark:bg-[#022c22] border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl text-xs sm:text-sm flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div
                class="bg-emerald-600 dark:bg-[#022c22] border border-emerald-500 dark:border-emerald-900/60 rounded-2xl p-6 sm:p-8 relative overflow-hidden shadow-md shadow-emerald-500/10 dark:shadow-none">
                <div
                    class="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#ffffff_1px,transparent_1px),linear-gradient(to_bottom,#ffffff_1px,transparent_1px)] bg-[size:24px_24px]">
                </div>

                <div class="relative z-10">
                    <div
                        class="inline-flex items-center gap-2 bg-emerald-700/50 dark:bg-emerald-950/50 border border-emerald-500/50 dark:border-emerald-800/50 rounded-md px-2.5 py-1 mb-2">
                        <i class="fa-solid fa-users text-emerald-200 dark:text-emerald-500 text-[10px]"></i>
                        <span
                            class="text-[9px] uppercase font-bold text-emerald-100 dark:text-emerald-400 tracking-widest">TOTAL
                            SALDO BERSAMA</span>
                    </div>
                    <p class="text-4xl sm:text-5xl font-black mt-2 tracking-tight text-white">
                        Rp {{ number_format($groupBalance, 0, ',', '.') }}
                    </p>

                    <div
                        class="grid grid-cols-2 gap-4 mt-8 pt-5 border-t border-emerald-500/50 dark:border-emerald-800/50">
                        <div
                            class="bg-emerald-700/30 dark:bg-[#064e3b]/30 p-3.5 rounded-xl border border-emerald-500/30 dark:border-emerald-800/30">
                            <p class="text-[11px] text-emerald-200 dark:text-emerald-500 font-semibold mb-1">Pemasukan
                                Total</p>
                            <p class="text-base font-bold text-white">Rp {{ number_format($totalIncome, 0, ',', '.') }}
                            </p>
                        </div>
                        <div
                            class="bg-emerald-700/30 dark:bg-[#064e3b]/30 p-3.5 rounded-xl border border-emerald-500/30 dark:border-emerald-800/30">
                            <p class="text-[11px] text-emerald-200 dark:text-emerald-500 font-semibold mb-1">Pengeluaran
                                Total</p>
                            <p class="text-base font-bold text-white">Rp {{ number_format($totalOutcome, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 sm:p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-6">
                            <i class="fa-solid fa-chart-pie text-emerald-500 text-sm"></i>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Kontribusi Anggota</h3>
                        </div>

                        <div
                            class="divide-y divide-slate-100 dark:divide-slate-800 max-h-[350px] overflow-y-auto pr-2 space-y-3 scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-800">
                            @foreach ($memberSavings as $member)
                                <div class="py-2">
                                    <div class="flex justify-between items-center mb-2">
                                        <div class="flex items-center gap-2.5">
                                            <div
                                                class="w-7 h-7 rounded-full bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-[10px] uppercase">
                                                {{ substr($member['name'], 0, 2) }}
                                            </div>
                                            <span
                                                class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $member['name'] }}</span>
                                        </div>
                                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-500">
                                            Rp {{ number_format($member['total'], 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div
                                        class="w-full bg-slate-100 dark:bg-slate-950 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-[#10b981] h-1.5 rounded-full transition-all duration-500"
                                            style="width: {{ $groupBalance > 0 ? max(0, min(100, ($member['total'] / $groupBalance) * 100)) : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div
                    class="lg:col-span-2 bg-white dark:bg-slate-900 shadow-sm rounded-2xl border border-slate-200 dark:border-slate-800 p-5 sm:p-6 space-y-5 h-[450px] flex flex-col">
                    <div class="flex items-center gap-2 shrink-0">
                        <i class="fa-solid fa-list-ul text-emerald-500 text-sm"></i>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Riwayat Mutasi Kelompok</h3>
                    </div>

                    <div
                        class="flex-1 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-800 rounded-xl border border-slate-200 dark:border-slate-800">
                        <table
                            class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-xs bg-slate-50/50 dark:bg-slate-950/50">
                            <thead
                                class="sticky top-0 bg-slate-100 dark:bg-slate-900 z-10 shadow-sm border-b border-slate-200 dark:border-slate-800">
                                <tr
                                    class="text-left font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[10px]">
                                    <th class="px-5 py-4">TANGGAL & MEMBER</th>
                                    <th class="px-5 py-4">KETERANGAN</th>
                                    <th class="px-5 py-4 text-right">JUMLAH</th>
                                    <th class="px-5 py-4 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80">
                                @forelse($groupTransactions as $gtx)
                                    <tr
                                        class="hover:bg-slate-100/50 dark:hover:bg-slate-800/30 transition duration-150">
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <p class="text-slate-700 dark:text-slate-300 font-medium">
                                                {{ \Carbon\Carbon::parse($gtx->created_at)->translatedFormat('d M Y • H:i') }}
                                            </p>
                                            <p
                                                class="text-[10px] text-slate-500 dark:text-slate-500 mt-1 flex items-center gap-1.5">
                                                <i class="fa-solid fa-user text-[9px]"></i> {{ $gtx->user->name }}
                                            </p>
                                        </td>
                                        <td
                                            class="px-5 py-4 text-slate-600 dark:text-slate-400 font-medium max-w-xs truncate">
                                            {{ $gtx->description }}
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap font-bold text-right text-sm">
                                            <span
                                                class="{{ $gtx->type == 'income' ? 'text-emerald-600 dark:text-emerald-500' : 'text-rose-600 dark:text-rose-500' }}">
                                                {{ $gtx->type == 'income' ? '+' : '-' }} Rp
                                                {{ number_format($gtx->amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap text-center text-sm">
                                            @if ($gtx->user_id === auth()->id())
                                                <div class="flex items-center justify-center gap-3">
                                                    <button
                                                        @click="editOpen = true; editId = '{{ $gtx->id }}'; editType = '{{ $gtx->type }}'; editAmount = '{{ $gtx->amount }}'; editDesc = '{{ addslashes($gtx->description) }}'; editDate = '{{ \Carbon\Carbon::parse($gtx->date)->format('Y-m-d') }}'"
                                                        class="text-slate-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-500 transition"
                                                        title="Edit Transaksi">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>

                                                    <form action="{{ route('transactions.destroy', $gtx->id) }}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Saldo grup akan disesuaikan otomatis.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-slate-400 dark:text-slate-500 hover:text-rose-600 dark:hover:text-rose-500 transition"
                                                            title="Hapus Transaksi">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span
                                                    class="text-slate-400 dark:text-slate-600 text-[10px] italic">Bukan
                                                    pemilik</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-5 py-10 text-center text-slate-500 dark:text-slate-600 font-medium">
                                            Belum ada riwayat mutasi tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="open || editOpen" class="fixed inset-0 bg-slate-900/40 dark:bg-slate-950/80 backdrop-blur-sm z-50"
            style="display: none;" x-transition></div>

        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
            style="display: none;" x-transition>
            <div @click.outside="open = false"
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl max-w-sm w-full overflow-hidden transform transition-all">
                <div
                    class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-950/40">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-money-bill-transfer text-emerald-500"></i> Catat Keuangan
                    </h3>
                    <button @click="open = false"
                        class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <form action="{{ route('groups.transaction.store', $group->id) }}" method="POST"
                    class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Jenis
                            Transaksi</label>
                        <select name="type"
                            class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                            <option value="income"> Setor Uang (Masuk Kas)</option>
                            <option value="outcome"> Tarik Uang (Keluar Kas)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Jumlah
                            Nominal (Rp)</label>
                        <input type="number" name="amount" x-model="amount" min="1" required
                            placeholder="Contoh: 100000"
                            class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 text-xs focus:border-emerald-500 focus:ring-0 placeholder-slate-400 dark:placeholder-slate-700">

                        <div class="mt-2.5 grid grid-cols-4 gap-2">
                            <button type="button" @click="amount = 10000"
                                class="py-1.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-600 dark:text-slate-400 rounded-lg text-[10px] font-bold transition-colors">10.000</button>
                            <button type="button" @click="amount = 20000"
                                class="py-1.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-600 dark:text-slate-400 rounded-lg text-[10px] font-bold transition-colors">20.000</button>
                            <button type="button" @click="amount = 50000"
                                class="py-1.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-600 dark:text-slate-400 rounded-lg text-[10px] font-bold transition-colors">50.000</button>
                            <button type="button" @click="amount = 100000"
                                class="py-1.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-600 dark:text-slate-400 rounded-lg text-[10px] font-bold transition-colors">100.000</button>
                            <button type="button" @click="amount = 200000"
                                class="py-1.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-600 dark:text-slate-400 rounded-lg text-[10px] font-bold transition-colors">200.000</button>
                            <button type="button" @click="amount = 500000"
                                class="py-1.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-600 dark:text-slate-400 rounded-lg text-[10px] font-bold transition-colors">500.000</button>
                            <button type="button" @click="amount = 1000000"
                                class="col-span-2 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-900/50 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 hover:border-emerald-500 text-emerald-600 dark:text-emerald-500 rounded-lg text-[10px] font-bold transition-colors">1
                                Juta</button>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Keterangan</label>
                        <input type="text" name="description" required placeholder="Contoh: Iuran kas bulan ini"
                            class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0 placeholder-slate-400 dark:placeholder-slate-700">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Tanggal</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required
                            class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                    </div>
                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="open = false"
                            class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white dark:text-slate-950 rounded-xl text-xs font-bold transition">Simpan
                            Catatan</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="editOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
            style="display: none;" x-transition>
            <div @click.outside="editOpen = false"
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl max-w-sm w-full overflow-hidden transform transition-all">
                <div
                    class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-950/40">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-emerald-500"></i> Edit Transaksi
                    </h3>
                    <button @click="editOpen = false"
                        class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <form :action="`{{ url('transactions') }}/${editId}`" method="POST" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Jenis
                            Transaksi</label>
                        <select name="type" x-model="editType"
                            class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                            <option value="income"> Setor Uang (Masuk Kas)</option>
                            <option value="outcome"> Tarik Uang (Keluar Kas)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Jumlah
                            Nominal (Rp)</label>
                        <input type="number" name="amount" x-model="editAmount" min="1" required
                            class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 text-xs focus:border-emerald-500 focus:ring-0 placeholder-slate-400 dark:placeholder-slate-700">

                        <div class="mt-2.5 grid grid-cols-4 gap-2">
                            <button type="button" @click="editAmount = 10000"
                                class="py-1.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-600 dark:text-slate-400 rounded-lg text-[10px] font-bold transition-colors">10.000</button>
                            <button type="button" @click="editAmount = 20000"
                                class="py-1.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-600 dark:text-slate-400 rounded-lg text-[10px] font-bold transition-colors">20.000</button>
                            <button type="button" @click="editAmount = 50000"
                                class="py-1.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-600 dark:text-slate-400 rounded-lg text-[10px] font-bold transition-colors">50.000</button>
                            <button type="button" @click="editAmount = 100000"
                                class="py-1.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-600 dark:text-slate-400 rounded-lg text-[10px] font-bold transition-colors">100.000</button>
                            <button type="button" @click="editAmount = 200000"
                                class="py-1.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-600 dark:text-slate-400 rounded-lg text-[10px] font-bold transition-colors">200.000</button>
                            <button type="button" @click="editAmount = 500000"
                                class="py-1.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-600 dark:text-slate-400 rounded-lg text-[10px] font-bold transition-colors">500.000</button>
                            <button type="button" @click="editAmount = 1000000"
                                class="col-span-2 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-900/50 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 hover:border-emerald-500 text-emerald-600 dark:text-emerald-500 rounded-lg text-[10px] font-bold transition-colors">1
                                Juta</button>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Keterangan</label>
                        <input type="text" name="description" x-model="editDesc" required
                            class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0 placeholder-slate-400 dark:placeholder-slate-700">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Tanggal</label>
                        <input type="date" name="date" x-model="editDate" required
                            class="block w-full rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                    </div>
                    <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-2">
                        <button type="button" @click="editOpen = false"
                            class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white dark:text-slate-950 rounded-xl text-xs font-bold shadow-md transition">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
