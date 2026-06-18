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

        // 1. Ambil 7 transaksi pribadi terbaru (untuk tabel dan chart)
        // Kita tambah orderBy 'id' agar jika ada transaksi di tanggal yg sama, urutannya tetap kronologis
        $transactions = $user->transactions()
            ->whereNull('group_id')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->take(7)
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
        $kasBersamaList = Group::whereHas('users', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        // Ambil riwayat gabungan (Personal + Grup) untuk mutasi di dashboard
        $recentTransactions = Transaction::where(function($query) use ($user) {
                $query->where('user_id', $user->id)->whereNull('group_id');
            })
            ->orWhereIn('group_id', $kasBersamaList->pluck('id'))
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->take(7)
            ->get();
        // =========================================================================

        // =========================================================================
        // 5. DATA UNTUK CHART (Sinkron dengan Transaksi Individu User Kiri ke Kanan)
        // =========================================================================
        
        // Balik 7 transaksi terbaru menjadi urutan Terlama -> Terbaru (Kiri ke Kanan)
        $chartTransactions = $transactions->reverse();
        
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
            // Label berupa Tanggal (bisa disesuaikan jika ingin tambah jam)
            $chartLabels[] = Carbon::parse($tx->date)->format('d M');

            // Naik jika Pemasukan, Turun jika Pengeluaran
            if ($tx->type == 'income') {
                $runningBalance += $tx->amount;
            } else {
                $runningBalance -= $tx->amount;
            }

            // Simpan posisi titik saldo di transaksi ini
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