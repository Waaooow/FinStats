<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * MENAMPILKAN HALAMAN UTAMA TABUNGAN BERSAMA
     */
    public function index()
    {
        $user = auth()->user();

        // Ambil semua grup yang diikuti oleh user yang sedang login
        $groups = $user->groups()->with(['users'])->get();

        return view('groups.index', compact('groups'));
    }

    /**
     * MEMBUAT KELOMPOK TABUNGAN BARU
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Generate token acak unik sepanjang 6 karakter untuk kode join (Contoh: KAS-A7B8)
        $code = 'KAS-'.strtoupper(Str::random(6));

        // 1. Simpan kelompok ke tabel 'groups'
        $group = Group::create([
            'name' => $request->name,
            'code' => $code,
        ]);

        // 2. Otomatis masukkan si pembuat grup sebagai anggota pertama di tabel jembatan 'group_user'
        $group->users()->attach(auth()->id());

        return redirect()->back()->with('success', 'Kelompok tabungan berhasil dibuat! Kode Join: '.$code);
    }

    /**
     * BERGABUNG KE KELOMPOK YANG SUDAH ADA LEWAT KODE JOIN
     */
    public function join(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        // Cari grup berdasarkan kode token yang dimasukkan
        $group = Group::where('code', strtoupper($request->code))->first();

        if (! $group) {
            return redirect()->back()->with('error', 'Kode kelompok tidak ditemukan! Periksa kembali kodenya.');
        }

        // Cek apakah user sudah terdaftar di grup tersebut agar tidak double
        if ($group->users()->where('user_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'Kamu sudah bergabung di kelompok ini!');
        }

        // Daftarkan user ke tabel jembatan group_user
        $group->users()->attach(auth()->id());

        return redirect()->back()->with('success', 'Berhasil bergabung ke kelompok: '.$group->name);
    }

    /**
     * MENAMPILKAN RINCIAN TOTAL DANA DAN KONTRIBUSI MEMBER DI DALAM GRUP
     */
    public function show($id)
    {
        // Pastikan user adalah anggota dari grup yang mau dia intip
        $group = Group::with('users')->findOrFail($id);
        if (! $group->users->contains(auth()->id())) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        // 1. Hitung Total Dana Masuk Kelompok
        $totalIncome = $group->transactions()->where('type', 'income')->sum('amount');

        // 2. Hitung Total Dana Keluar Kelompok
        $totalOutcome = $group->transactions()->where('type', 'outcome')->sum('amount');

        // 3. Saldo Bersih Kas Bersama
        $groupBalance = $totalIncome - $totalOutcome;

        // 4. HITUNG RINCIAN SALDO PER MEMBER (LEADERBOARD)
        // Kita looping setiap user di grup ini, lalu hitung sum(amount) mereka masing-masing
        $memberSavings = $group->users->map(function ($user) use ($group) {
            $userIncome = $group->transactions()->where('user_id', $user->id)->where('type', 'income')->sum('amount');
            $userOutcome = $group->transactions()->where('user_id', $user->id)->where('type', 'outcome')->sum('amount');

            return [
                'name' => $user->name,
                'total' => $userIncome - $userOutcome,
            ];
        })->sortByDesc('total'); // Urutkan dari yang menabungnya paling banyak

        // 5. Riwayat Aktivitas Transaksi di dalam grup ini
        $groupTransactions = $group->transactions()->with('user')->orderBy('date', 'desc')->get();

        return view('groups.show', compact('group', 'groupBalance', 'memberSavings', 'groupTransactions'));
    }

    /**
     * PROSES SETOR ATAU TARIK UANG DI TABUNGAN BERSAMA
     */
    public function storeTransaction(Request $request, $groupId)
    {
        $request->validate([
            'type' => 'required|in:income,outcome',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        Transaction::create([
            'user_id' => auth()->id(),
            'group_id' => $groupId, // Dikunci ke ID kelompok ini
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', 'Catatan keuangan kelompok berhasil disimpan!');
    }
}
