<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-chart-pie text-indigo-600"></i>
            {{ __('Dashboard Keuangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-500 text-white p-6 rounded-lg shadow">
                    <p class="text-sm uppercase font-semibold opacity-75">Saldo Tabungan</p>
                    <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="bg-green-500 text-white p-6 rounded-lg shadow">
                    <p class="text-sm uppercase font-semibold opacity-75">Total Pemasukan</p>
                    <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="bg-red-500 text-white p-6 rounded-lg shadow">
                    <p class="text-sm uppercase font-semibold opacity-75">Total Pengeluaran</p>
                    <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow space-y-4">
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-semibold text-gray-700">Tren Saldo Tabungan (7 Transaksi Terakhir)</h3>
                </div>
                <div class="w-full overflow-x-auto">
                    <div class="min-w-[600px] h-64 sm:h-80">
                        <canvas id="pribadiChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                <div class="bg-white p-6 rounded-lg shadow lg:col-span-1">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">Catat Transaksi</h3>
                    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipe</label>
                            <select name="type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="income">Pemasukan (Income)</option>
                                <option value="outcome">Pengeluaran (Outcome)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
                            <input type="number" name="amount" min="1" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <input type="text" name="description" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <button type="submit"
                            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 font-semibold shadow">
                            Simpan Transaksi
                        </button>
                    </form>
                </div>

                <div class="bg-white p-6 rounded-lg shadow lg:col-span-2 overflow-hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Riwayat Transaksi</h3>

                        <a href="{{ route('transactions.export') }}"
                            class="bg-gray-800 text-white px-3 py-1.5 rounded text-sm font-medium hover:bg-gray-700 transition">
                            Ekspor Rekap (.xlsx)
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Keterangan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipe</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($transactions ?? [] as $tx)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($tx->date)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            {{ $tx->description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tx->type == 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $tx->type == 'income' ? 'Income' : 'Outcome' }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $tx->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $tx->type == 'income' ? '+' : '-' }} Rp
                                            {{ number_format($tx->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-400">
                                            Belum ada riwayat transaksi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('pribadiChart').getContext('2d');

        const labelsData = {!! json_encode($chartLabels ?? []) !!};
        const valuesData = {!! json_encode($chartData ?? []) !!};

        // Efek gradasi linear mewah di bawah garis
        const gradientBg = ctx.createLinearGradient(0, 0, 0, 300);
        gradientBg.addColorStop(0, 'rgba(79, 70, 229, 0.25)');
        gradientBg.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsData,
                datasets: [{
                    label: 'Saldo Berjalan (Rp)',
                    data: valuesData,
                    borderColor: '#4f46e5',
                    backgroundColor: gradientBg,
                    borderWidth: 3.5,
                    tension: 0.38,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2.5,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.04)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
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
