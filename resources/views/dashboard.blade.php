<div x-data="{ open: false, amount: '' }">
    <x-app-layout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-8 pb-12">
            
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-chart-simple text-emerald-500 text-xl"></i>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-100 tracking-tight">Dashboard Keuangan</h2>
                </div>

                <button @click="open = true" class="px-4 py-2.5 bg-[#10b981] hover:bg-[#059669] text-slate-950 text-xs font-bold rounded-full shadow-sm flex items-center gap-1.5 transition-all duration-200">
                    <i class="fa-solid fa-plus-circle text-sm"></i> Catat Transaksi
                </button>
            </div>

            @if (session('success'))
                <div class="bg-[#022c22] border border-emerald-800 text-emerald-400 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 {{ $kasBersamaList->count() > 0 ? 'lg:grid-cols-2' : 'grid-cols-1' }} gap-6">
                <div class="bg-[#022c22] rounded-2xl border border-emerald-900/60 p-6 sm:p-8 relative overflow-hidden group">
                    <div class="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#ffffff_1px,transparent_1px),linear-gradient(to_bottom,#ffffff_1px,transparent_1px)] bg-[size:24px_24px]"></div>
                    <div class="relative z-10 space-y-4">
                        <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest bg-emerald-950/50 border border-emerald-800/50 px-2.5 py-1 rounded-md">
                            TOTAL SALDO TABUNGAN
                        </span>
                        <div class="text-4xl sm:text-5xl font-black text-white tracking-tight">
                            Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-emerald-800/50 mt-6">
                            <div class="bg-[#064e3b]/30 p-3.5 rounded-xl border border-emerald-800/30">
                                <p class="text-[11px] text-emerald-500 font-semibold mb-1">Pemasukan</p>
                                <p class="text-base font-bold text-white">Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-[#064e3b]/30 p-3.5 rounded-xl border border-emerald-800/30">
                                <p class="text-[11px] text-emerald-500 font-semibold mb-1">Pengeluaran</p>
                                <p class="text-base font-bold text-white">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach($kasBersamaList as $kas)
                <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 sm:p-8 relative overflow-hidden flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest bg-slate-950 px-2.5 py-1 rounded-md border border-slate-800">
                                TABUNGAN BERSAMA
                            </span>
                            <span class="text-xs text-slate-400 font-semibold bg-slate-950 px-2.5 py-1 rounded-lg border border-slate-800">
                                {{ $kas->name ?? $kas->nama_tabungan }}
                            </span>
                        </div>
                        <div class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                            Rp {{ number_format($kas->transactions()->where('type', 'income')->sum('amount') - $kas->transactions()->where('type', 'outcome')->sum('amount'), 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400">
                        <span class="text-slate-500">Grup Aktif</span>
                        <a href="{{ route('groups.show', $kas->id) }}" class="text-emerald-500 hover:text-emerald-400 font-bold flex items-center gap-1 transition-colors">
                            Lihat Detail <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-7 bg-slate-900 border border-slate-800 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-emerald-500 text-sm"></i>
                        <h3 class="text-sm sm:text-base font-bold text-slate-200">Tren Keuangan <span class="text-xs text-slate-500 font-normal">(7 Transaksi Terakhir)</span></h3>
                    </div>
                    <div class="w-full overflow-x-auto">
                        <div class="min-w-[500px] h-64 sm:h-80">
                            <canvas id="pribadiChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 bg-slate-900 border border-slate-800 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-receipt text-emerald-500 text-sm"></i>
                            <div>
                                <h3 class="text-sm font-bold text-slate-200">Riwayat Mutasi</h3>
                                <p class="text-[10px] text-slate-500">Daftar keluar masuk uang pribadi kamu</p>
                            </div>
                        </div>
                        @if (isset($transactions) && $transactions->count() > 0)
                            <a href="{{ route('transactions.export') }}" class="inline-flex items-center justify-center gap-1.5 bg-slate-950 border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 transition px-3 py-1.5 rounded-lg text-[10px] font-bold self-start sm:self-center">
                                <i class="fa-solid fa-file-excel text-emerald-500"></i> Ekspor (.xlsx)
                            </a>
                        @endif
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-slate-800">
                        <table class="min-w-full divide-y divide-slate-800 text-xs text-left bg-slate-950/50">
                            <thead>
                                <tr class="font-semibold text-slate-400 uppercase tracking-wider text-[10px]">
                                    <th class="px-4 py-3.5">Tanggal</th>
                                    <th class="px-4 py-3.5">Keterangan</th>
                                    <th class="px-4 py-3.5 text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/80">
                                @forelse($transactions ?? [] as $tx)
                                    <tr class="hover:bg-slate-800/30 transition duration-150">
                                        <td class="px-4 py-4 whitespace-nowrap font-medium text-slate-300">
                                            {{ \Carbon\Carbon::parse($tx->date)->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-4 font-medium text-slate-400 max-w-xs truncate">
                                            {{ $tx->description }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap font-bold text-right text-sm">
                                            <span class="{{ $tx->type == 'income' ? 'text-emerald-500' : 'text-rose-500' }}">
                                                {{ $tx->type == 'income' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-10 text-center text-slate-500 font-medium">
                                            Belum ada catatan transaksi pribadi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" style="display: none;" x-transition>
            <div @click.outside="open = false" class="bg-slate-900 rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-slate-800 transform transition-all">
                <div class="px-5 py-4 bg-slate-950/60 border-b border-slate-800 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-money-bill-transfer text-emerald-500"></i> Catat Transaksi Keuangan
                    </h3>
                    <button @click="open = false" class="text-slate-500 hover:text-white text-xs"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form action="{{ route('transactions.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Jenis Transaksi</label>
                        <select name="type" class="block w-full rounded-xl bg-slate-950 border-slate-800 text-slate-200 text-xs focus:border-emerald-500 focus:ring-0">
                            <option value="income">🟢 Pemasukan (Uang Masuk)</option>
                            <option value="outcome">🔴 Pengeluaran (Uang Keluar)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Jumlah Nominal (Rp)</label>
                        <input type="number" name="amount" x-model="amount" min="1" required placeholder="Contoh: 50000" class="block w-full rounded-xl bg-slate-950 border-slate-800 text-slate-200 text-xs focus:border-emerald-500 focus:ring-0 placeholder-slate-700">
                        
                        <div class="mt-2.5 grid grid-cols-4 gap-2">
                            <button type="button" @click="amount = 10000" class="py-1.5 bg-slate-950/80 border border-slate-800 hover:border-emerald-500 hover:text-emerald-400 text-slate-400 rounded-lg text-[10px] font-bold transition-colors">10.000</button>
                            <button type="button" @click="amount = 20000" class="py-1.5 bg-slate-950/80 border border-slate-800 hover:border-emerald-500 hover:text-emerald-400 text-slate-400 rounded-lg text-[10px] font-bold transition-colors">20.000</button>
                            <button type="button" @click="amount = 50000" class="py-1.5 bg-slate-950/80 border border-slate-800 hover:border-emerald-500 hover:text-emerald-400 text-slate-400 rounded-lg text-[10px] font-bold transition-colors">50.000</button>
                            <button type="button" @click="amount = 100000" class="py-1.5 bg-slate-950/80 border border-slate-800 hover:border-emerald-500 hover:text-emerald-400 text-slate-400 rounded-lg text-[10px] font-bold transition-colors">100.000</button>
                            <button type="button" @click="amount = 200000" class="py-1.5 bg-slate-950/80 border border-slate-800 hover:border-emerald-500 hover:text-emerald-400 text-slate-400 rounded-lg text-[10px] font-bold transition-colors">200.000</button>
                            <button type="button" @click="amount = 500000" class="py-1.5 bg-slate-950/80 border border-slate-800 hover:border-emerald-500 hover:text-emerald-400 text-slate-400 rounded-lg text-[10px] font-bold transition-colors">500.000</button>
                            <button type="button" @click="amount = 1000000" class="col-span-2 py-1.5 bg-emerald-900/20 border border-emerald-900/50 hover:bg-emerald-900/40 hover:border-emerald-500 text-emerald-500 rounded-lg text-[10px] font-bold transition-colors">1 Juta</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Keterangan</label>
                        <input type="text" name="description" required placeholder="Contoh: Gaji bulanan, beli kopi" class="block w-full rounded-xl bg-slate-950 border-slate-800 text-slate-200 text-xs focus:border-emerald-500 focus:ring-0 placeholder-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Tanggal</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="block w-full rounded-xl bg-slate-950 border-slate-800 text-slate-200 text-xs focus:border-emerald-500 focus:ring-0">
                    </div>
                    <div class="pt-3 border-t border-slate-800 flex justify-end gap-2">
                        <button type="button" @click="open = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-semibold transition">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#10b981] hover:bg-[#059669] text-slate-950 rounded-xl text-xs font-bold shadow-md transition">Simpan Catatan</button>
                    </div>
                </form>
            </div>
        </div>
    </x-app-layout>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('pribadiChart').getContext('2d');
        const labelsData = {!! json_encode($chartLabels ?? []) !!};
        const valuesData = {!! json_encode($chartData ?? []) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsData,
                datasets: [{
                    label: 'Aktivitas Finansial (Rp)',
                    data: valuesData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.04)',
                    borderWidth: 3,
                    tension: 0.38,
                    fill: true,
                    pointBackgroundColor: '#10b981',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(255, 255, 255, 0.03)' }, ticks: { color: '#64748b' } },
                    x: { grid: { display: false }, ticks: { color: '#64748b' } }
                }
            }
        });
    });
</script>