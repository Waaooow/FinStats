<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $groups = $user->groups()->with(['users'])->get();
        return view('groups.index', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $code = 'KAS-'.strtoupper(Str::random(6));

        $group = Group::create([
            'name' => $request->name,
            'code' => $code,
        ]);

        $group->users()->attach(auth()->id());

        return redirect()->back()->with('success', 'Kelompok tabungan berhasil dibuat! Kode Join: '.$code);
    }

    public function join(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $group = Group::where('code', strtoupper($request->code))->first();

        if (! $group) {
            return redirect()->back()->with('error', 'Kode kelompok tidak ditemukan! Periksa kembali kodenya.');
        }

        if ($group->users()->where('user_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'Kamu sudah bergabung di kelompok ini!');
        }

        $group->users()->attach(auth()->id());

        return redirect()->back()->with('success', 'Berhasil bergabung ke kelompok: '.$group->name);
    }

    public function show($id)
    {
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

        // 4. Hitung rincian saldo per member
        $memberSavings = $group->users->map(function ($user) use ($group) {
            $userIncome = $group->transactions()->where('user_id', $user->id)->where('type', 'income')->sum('amount');
            $userOutcome = $group->transactions()->where('user_id', $user->id)->where('type', 'outcome')->sum('amount');

            return [
                'name' => $user->name,
                'total' => $userIncome - $userOutcome,
            ];
        })->sortByDesc('total');

        // 5. Riwayat Aktivitas
        $groupTransactions = $group->transactions()->with('user')->orderBy('date', 'desc')->get();

        // Variabel totalIncome & totalOutcome sudah dipassing ke view!
        return view('groups.show', compact('group', 'groupBalance', 'totalIncome', 'totalOutcome', 'memberSavings', 'groupTransactions'));
    }

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
            'group_id' => $groupId,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', 'Catatan keuangan kelompok berhasil disimpan!');
    }
}