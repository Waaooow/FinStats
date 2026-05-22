<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div x-data="{ open: false }">
    <x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center px-2 sm:px-0">
                <div>
                    <a href="{{ route('groups.index') }}"
                        class="text-xs text-indigo-600 font-bold hover:underline inline-flex items-center gap-1">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke List
                    </a>
                    <h2 class="font-bold text-lg sm:text-xl text-gray-800 leading-tight mt-1 flex items-center gap-2">
                        <i class="fa-solid fa-users text-indigo-600"></i>
                        {{ $group->name }}
                    </h2>
                </div>

                <button @click="open = true"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-full shadow-md text-xs transition-all duration-200 hover:scale-105 flex items-center gap-1.5">
                    <i class="fa-solid fa-plus-circle text-sm"></i> Setor / Tarik Uang
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
                    class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-white rounded-2xl shadow-lg p-6 sm:p-8 relative overflow-hidden group">
                    <div
                        class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition duration-500">
                    </div>

                    <div class="relative z-10 text-center sm:text-left">
                        <p
                            class="text-[10px] sm:text-xs uppercase font-extrabold text-indigo-100 tracking-wider flex items-center justify-center sm:justify-start gap-1.5">
                            <i class="fa-solid fa-vault text-indigo-200"></i> Total Kas / Saldo Bersama Kelompok
                        </p>
                        <p class="text-3xl sm:text-5xl font-black mt-2 drop-shadow-sm tracking-tight">
                            Rp {{ number_format($groupBalance, 0, ',', '.') }}
                        </p>
                        <p class="text-[11px] text-indigo-100/80 mt-3 font-medium">
                            <i class="fa-solid fa-circle-info"></i> Gabungan akumulasi dana aktif dari seluruh anggota
                            kelompok terdaftar.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div
                        class="md:col-span-1 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <div
                                    class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-chart-pie"></i>
                                </div>
                                <h3 class="text-sm sm:text-base font-bold text-gray-800">Kontribusi Anggota</h3>
                            </div>
                            <p class="text-[11px] text-gray-400">Rincian kontribusi nominal tabungan riil per anggota.
                            </p>

                            <div class="divide-y divide-gray-50 max-h-80 overflow-y-auto mt-4 pr-1">
                                @foreach ($memberSavings as $member)
                                    <div class="py-3.5 space-y-2">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-2.5">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-50 to-purple-50 text-indigo-600 border border-indigo-100 flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                                                    {{ substr($member['name'], 0, 2) }}
                                                </div>
                                                <span
                                                    class="text-xs font-bold text-gray-700 tracking-tight">{{ $member['name'] }}</span>
                                            </div>
                                            <span
                                                class="text-xs font-extrabold {{ $member['total'] >= 0 ? 'text-indigo-600' : 'text-rose-600' }}">
                                                Rp {{ number_format($member['total'], 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-gradient-to-r from-indigo-600 to-purple-500 h-1.5 rounded-full"
                                                style="width: {{ $groupBalance > 0 ? max(0, min(100, ($member['total'] / $groupBalance) * 100)) : 0 }}%">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-list-ul"></i>
                            </div>
                            <h3 class="text-sm sm:text-base font-bold text-gray-800">Riwayat Mutasi Kelompok</h3>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-gray-100">
                            <table class="min-w-full divide-y divide-gray-100 text-xs">
                                <thead>
                                    <tr
                                        class="text-left font-bold text-gray-500 bg-gray-50/70 uppercase tracking-wider text-[10px]">
                                        <th class="px-4 py-3">Tanggal & Member</th>
                                        <th class="px-4 py-3">Keterangan</th>
                                        <th class="px-4 py-3 text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse($groupTransactions as $gtx)
                                        <tr class="hover:bg-gray-50/50 transition duration-150">
                                            <td class="px-4 py-3.5 whitespace-nowrap">
                                                <p class="text-gray-700 font-semibold">
                                                    {{ \Carbon\Carbon::parse($gtx->date)->format('d M Y') }}</p>
                                                <p
                                                    class="text-[10px] text-indigo-600 font-extrabold mt-0.5 inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-user text-[9px]"></i> {{ $gtx->user->name }}
                                                </p>
                                            </td>
                                            <td class="px-4 py-3.5 font-medium text-gray-800 max-w-xs truncate">
                                                {{ $gtx->description }}
                                            </td>
                                            <td class="px-4 py-3.5 whitespace-nowrap font-black text-right text-sm">
                                                <span
                                                    class="{{ $gtx->type == 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                                    {{ $gtx->type == 'income' ? '+' : '-' }} Rp
                                                    {{ number_format($gtx->amount, 0, ',', '.') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-8 text-center text-gray-400 font-medium">
                                                <i class="fa-solid fa-inbox text-lg block mb-1 text-gray-300"></i>
                                                Belum ada mutasi tercatat di kelompok ini.
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

        <div x-show="open"
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            style="display: none;" x-transition>
            <div @click.outside="open = false"
                class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-gray-100 transform transition-all">
                <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-money-bill-transfer text-indigo-600"></i> Catat Keuangan Kelompok
                    </h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-xs">✕</button>
                </div>
                <form action="{{ route('groups.transaction.store', $group->id) }}" method="POST"
                    class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Transaksi</label>
                        <select name="type"
                            class="block w-full rounded-xl border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="income">🟢 Setor Uang (Masuk Kas)</option>
                            <option value="outcome">🔴 Tarik Uang (Keluar Kas)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Nominal (Rp)</label>
                        <input type="number" name="amount" min="1" required placeholder="Contoh: 100000"
                            class="block w-full rounded-xl border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan Aktivitas</label>
                        <input type="text" name="description" required placeholder="Contoh: Iuran kas bulan ini"
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
