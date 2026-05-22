<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div x-data="{ open: false }">
    <x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center px-2 sm:px-0">
                <h2 class="font-bold text-lg sm:text-xl text-gray-800 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-indigo-600"></i>
                    {{ __('Dashboard Keuangan') }}
                </h2>

                <button @click="open = true"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-full shadow-md text-xs transition-all duration-200 hover:scale-105 flex items-center gap-1.5">
                    <i class="fa-solid fa-plus-circle text-sm"></i> Catat Transaksi
                </button>
            </div>
        </x-slot>

        <div class="py-6 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                @if (session('success'))
                    <div
                        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-circle-check text-green-500"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <div
                    class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-white rounded-2xl shadow-lg overflow-hidden group relative">
                    <div
                        class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition duration-500">
                    </div>

                    <div class="p-6 sm:p-8 text-left relative z-10 border-b border-white/10">
                        <p
                            class="text-[10px] sm:text-xs uppercase font-extrabold text-indigo-100 tracking-wider flex items-center justify-start gap-1.5">
                            <i class="fa-solid fa-wallet text-indigo-200"></i> Total Saldo Tabungan
                        </p>
                        <p class="text-3xl sm:text-5xl font-black mt-2 drop-shadow-sm tracking-tight text-left">
                            Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}
                        </p>
                    </div>

                    <div
                        class="grid grid-cols-2 bg-slate-900/40 backdrop-blur-xs text-left text-xs divide-x divide-white/10 relative z-10">
                        <div class="py-4 px-5 hover:bg-white/5 transition duration-200 text-left">
                            <p
                                class="text-[10px] sm:text-xs font-black tracking-wider uppercase text-emerald-400 flex items-center justify-start gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block animate-pulse"></span>
                                Pemasukan
                            </p>
                            <p class="text-sm sm:text-lg font-black mt-1 text-emerald-300">
                                Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="py-4 px-5 hover:bg-white/5 transition duration-200 text-left">
                            <p
                                class="text-[10px] sm:text-xs font-black tracking-wider uppercase text-rose-400 flex items-center justify-start gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-rose-400 inline-block"></span> Pengeluaran
                            </p>
                            <p class="text-sm sm:text-lg font-black mt-1 text-rose-300">
                                Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 sm:p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <div class="flex items-center gap-2">
                        <div
                            class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h3 class="text-sm sm:text-base font-bold text-gray-800">Tren Keuangan (7 Transaksi Terakhir)
                        </h3>
                    </div>

                    <div class="w-full overflow-x-auto">
                        <div class="min-w-[600px] h-64 sm:h-80">
                            <canvas id="pribadiChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 sm:p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-gray-800">Riwayat Mutasi</h3>
                                <p class="text-[10px] sm:text-xs text-gray-400">Daftar keluar masuknya uang pribadi kamu
                                </p>
                            </div>
                        </div>

                        @if (isset($transactions) && $transactions->count() > 0)
                            <a href="{{ route('transactions.export') }}"
                                class="inline-flex items-center justify-center gap-1.5 bg-gray-50 border border-gray-200 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-100 transition px-3 py-1.5 rounded-xl text-xs font-semibold self-start sm:self-center">
                                <i class="fa-solid fa-file-excel text-emerald-600"></i> Eksport (.xlsx)
                            </a>
                        @endif
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-gray-100">
                        <table class="min-w-full divide-y divide-gray-100 text-xs">
                            <thead>
                                <tr
                                    class="text-left font-bold text-gray-500 bg-gray-50/70 uppercase tracking-wider text-[10px]">
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Keterangan</th>
                                    <th class="px-4 py-3">Tipe</th>
                                    <th class="px-4 py-3 text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($transactions ?? [] as $tx)
                                    <tr class="hover:bg-gray-50/50 transition duration-150">
                                        <td class="px-4 py-3.5 whitespace-nowrap font-semibold text-gray-700">
                                            {{ \Carbon\Carbon::parse($tx->date)->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3.5 font-medium text-gray-800 max-w-xs truncate">
                                            {{ $tx->description }}
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap font-bold">
                                            <span
                                                class="px-2.5 py-1 rounded-full text-[10px] {{ $tx->type == 'income' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                                {{ $tx->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap font-black text-right text-sm">
                                            <span
                                                class="{{ $tx->type == 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $tx->type == 'income' ? '+' : '-' }} Rp
                                                {{ number_format($tx->amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-12 text-center text-gray-400 font-medium">
                                            <i class="fa-solid fa-folder-open text-2xl block mb-2 text-gray-300"></i>
                                            Belum ada catatan transaksi keuangan pribadi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <div x-show="open"
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            style="display: none;" x-transition>
            <div @click.outside="open = false"
                class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-gray-100 transform transition-all">
                <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-money-bill-transfer text-indigo-600"></i> Catat Transaksi Keuangan
                    </h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-xs">✕</button>
                </div>
                <form action="{{ route('transactions.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Transaksi</label>
                        <select name="type"
                            class="block w-full rounded-xl border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="income">🟢 Pemasukan (Uang Masuk)</option>
                            <option value="outcome">🔴 Pengeluaran (Uang Keluar)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Nominal (Rp)</label>
                        <input type="number" name="amount" min="1" required placeholder="Contoh: 50000"
                            class="block w-full rounded-xl border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan</label>
                        <input type="text" name="description" required placeholder="Contoh: Gaji bulanan, beli kopi"
                            class="block w-full rounded-xl border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required
                            class="block w-full rounded-xl border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                        <button type="button" @click="open = false"
                            class="px-3 py-1.5 border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-1.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl text-xs font-bold shadow-md transition hover:from-indigo-700 hover:to-purple-700">Simpan
                            Catatan</button>
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

        // Membaca data json dari controller laravel
        const labelsData = {!! json_encode($chartLabels ?? []) !!};
        const valuesData = {!! json_encode($chartData ?? []) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsData,
                datasets: [{
                    label: 'Aktivitas Finansial (Rp)',
                    data: valuesData,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.08)',
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#4f46e5',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Mematikan legend box atas biar clean
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.04)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
