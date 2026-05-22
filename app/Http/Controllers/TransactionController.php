<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class TransactionController extends Controller
{
    /**
     * MENAMPILKAN DASHBOARD UTAMA (TRANSAKSI PRIBADI)
     */
    public function index()
    {
        $user = auth()->user();

        // 1. Ambil transaksi pribadi saja (yang tidak gabung grup / group_id = null)
        $transactions = $user->transactions()
            ->whereNull('group_id')
            ->orderBy('date', 'desc')
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

        // 5. MEMBUAT DATA UNTUK CHART (Tren Saldo Berjalan dari Kiri ke Kanan)
        // Urutan dibalik: Tanggal lama (kiri) -> Tanggal baru (kanan)
        $chartTransactions = $transactions->reverse();

        $chartLabels = [];
        $chartData = [];

        // Hitung mundur saldo awal sebelum 7 transaksi terakhir ini terjadi
        $netChangeOfSeven = 0;
        foreach ($chartTransactions as $tx) {
            if ($tx->type == 'income') {
                $netChangeOfSeven += $tx->amount;
            } else {
                $netChangeOfSeven -= $tx->amount;
            }
        }

        // Ini titik start saldo awal pada grafik paling kiri
        $runningBalance = $totalSaldo - $netChangeOfSeven;

        // Iterasi maju untuk merekam pergerakan akumulasi saldo ke kanan
        foreach ($chartTransactions as $tx) {
            $chartLabels[] = Carbon::parse($tx->date)->format('d M');

            if ($tx->type == 'income') {
                $runningBalance += $tx->amount;
            } else {
                $runningBalance -= $tx->amount;
            }

            $chartData[] = (float) $runningBalance;
        }

        // 6. Oper semua variabel ke view dashboard
        return view('dashboard', compact(
            'transactions',
            'totalPemasukan',
            'totalPengeluaran',
            'totalSaldo',
            'chartLabels',
            'chartData'
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
            'group_id' => null, // Dipastikan null karena transaksi pribadi
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', 'Transaksi pribadi berhasil dicatat!');
    }

    /**
     * PROSES EKSPOR EXCEL MENGGUNAKAN PHPOFFICE / PHPSPREADSHEET
     */
    public function export()
    {
        $user = auth()->user();

        // Ambil data transaksi pribadi saja
        $transactions = $user->transactions()
            ->whereNull('group_id')
            ->orderBy('date', 'desc')
            ->get();

        // Inisialisasi Class dari PHPOffice
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // 1. Membuat Judul Kolom (Header)
        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Keterangan');
        $sheet->setCellValue('C1', 'Tipe');
        $sheet->setCellValue('D1', 'Nominal (Rp)');

        // Desain sedikit header-nya biar tebal (opsional)
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // 2. Memasukkan Data Transaksi looping dari baris ke-2
        $row = 2;
        foreach ($transactions as $tx) {
            $sheet->setCellValue('A'.$row, Carbon::parse($tx->date)->format('d/m/Y'));
            $sheet->setCellValue('B'.$row, $tx->description);
            $sheet->setCellValue('C'.$row, $tx->type == 'income' ? 'Pemasukan' : 'Pengeluaran');

            // Disimpan sebagai angka (numeric) agar bisa di-SUM otomatis di Excel
            $sheet->setCellValue('D'.$row, $tx->amount);
            $sheet->getStyle('D'.$row)->getNumberFormat()->setFormatCode('#,##0');

            $row++;
        }

        // Auto-size kolom biar tidak kepotong teksnya
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 3. Proses Download File .xlsx langsung ke Browser
        $fileName = 'Laporan_Transaksi_Pribadi_'.date('Ymd_His').'.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
