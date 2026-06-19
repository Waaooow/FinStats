<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class TransactionController extends Controller
{
    /**
     * MENAMPILKAN DASHBOARD UTAMA (PRIBADI & DINAMIS GRUP)
     */
    public function index()
    {
        $user = auth()->user();

        // 1a. Ambil 7 transaksi pribadi terbaru KHUSUS UNTUK CHART (agar grafik tidak kepanjangan)
        $latestForChart = $user->transactions()
            ->whereNull('group_id')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->take(7)
            ->get();

        // 1b. Ambil SEMUA transaksi pribadi KHUSUS UNTUK TABEL (Biar bisa di-scroll)
        $transactions = $user->transactions()
            ->whereNull('group_id')
            ->orderBy('created_at', 'desc') // Urut berdasarkan created_at agar jam/menit urut akurat
            ->get();

        // 2. Hitung total pemasukan pribadi
        $totalPemasukan = $user->transactions()
            ->whereNull('group_id')
            ->where('type', 'income')
            ->sum('amount');

        // 3. Hitung total pengeluaran pribadi
        $totalPengeluaran = $user->transactions()
            ->whereNull('group_id')
            ->where('type', 'outcome')
            ->sum('amount');

        // 4. Hitung sisa saldo bersih pribadi
        $totalSaldo = $totalPemasukan - $totalPengeluaran;

        // =========================================================================
        // AMBIL DATA TABUNGAN BERSAMA
        // =========================================================================
        $kasBersamaList = Group::whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        // Ambil riwayat gabungan (Personal + Grup)
        $recentTransactions = Transaction::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)->whereNull('group_id');
        })
            ->orWhereIn('group_id', $kasBersamaList->pluck('id'))
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->take(7)
            ->get();
        // =========================================================================

        // =========================================================================
        // 5. DATA UNTUK CHART (Sinkron Kiri ke Kanan)
        // =========================================================================

        // Balik 7 transaksi terbaru menjadi urutan Terlama -> Terbaru (Kiri ke Kanan)
        $chartTransactions = $latestForChart->reverse();

        $chartLabels = [];
        $chartData = [];

        // Hitung total perubahan uang khusus pada 7 transaksi ini saja
        $netChangeOfSeven = 0;
        foreach ($chartTransactions as $tx) {
            if ($tx->type == 'income') {
                $netChangeOfSeven += $tx->amount;
            } else {
                $netChangeOfSeven -= $tx->amount;
            }
        }

        // Tentukan "Saldo Start" tepat sebelum 7 transaksi ini terjadi
        $runningBalance = $totalSaldo - $netChangeOfSeven;

        // Looping per transaksi untuk menggambar titik naik/turun di Chart
        foreach ($chartTransactions as $tx) {
            $chartLabels[] = Carbon::parse($tx->date)->format('d M');

            if ($tx->type == 'income') {
                $runningBalance += $tx->amount;
            } else {
                $runningBalance -= $tx->amount;
            }

            $chartData[] = (float) $runningBalance;
        }
        // =========================================================================

        return view('dashboard', compact(
            'transactions',
            'totalPemasukan',
            'totalPengeluaran',
            'totalSaldo',
            'chartLabels',
            'chartData',
            'kasBersamaList',
            'recentTransactions'
        ));
    }

    /**
     * PROSES SIMPAN TRANSAKSI PRIBADI BARU
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,outcome',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        Transaction::create([
            'user_id' => auth()->id(),
            'group_id' => null,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', 'Transaksi pribadi berhasil dicatat!');
    }

    /**
     * PROSES EDIT/UPDATE TRANSAKSI (Pribadi & Grup)
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit transaksi ini.');
        }

        $request->validate([
            'type' => 'required|in:income,outcome',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $transaction->update([
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', 'Transaksi berhasil diperbarui!');
    }

    /**
     * PROSES HAPUS TRANSAKSI (Pribadi & Grup)
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus transaksi ini.');
        }

        $transaction->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus! Saldo otomatis disesuaikan.');
    }

    /**
     * PROSES EKSPOR EXCEL
     */
    public function export()
    {
        $user = auth()->user();

        $transactions = $user->transactions()
            ->whereNull('group_id')
            ->orderBy('date', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Keterangan');
        $sheet->setCellValue('C1', 'Tipe');
        $sheet->setCellValue('D1', 'Nominal (Rp)');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        $row = 2;
        foreach ($transactions as $tx) {
            $sheet->setCellValue('A'.$row, Carbon::parse($tx->date)->format('d/m/Y'));
            $sheet->setCellValue('B'.$row, $tx->description);
            $sheet->setCellValue('C'.$row, $tx->type == 'income' ? 'Pemasukan' : 'Pengeluaran');
            $sheet->setCellValue('D'.$row, $tx->amount);
            $sheet->getStyle('D'.$row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
        }

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Laporan_Transaksi_Pribadi_'.date('Ymd_His').'.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
