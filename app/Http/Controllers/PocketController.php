<?php

namespace App\Http\Controllers;

use App\Models\Pocket;
use App\Models\PocketTransaction;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PocketController extends Controller
{
    /**
     * TAMPILKAN DAFTAR POCKET PENGGUNA
     */
    public function index()
    {
        $user = Auth::user();
        $pockets = $user->pockets()->with('transactions')->get();

        $totalBalance = $pockets->sum('balance');
        $totalTarget = $pockets->whereNotNull('target')->sum('target');

        return view('pockets.index', compact('pockets', 'totalBalance', 'totalTarget'));
    }

    /**
     * TAMPILKAN FORM BUAT POCKET BARU
     */
    public function create()
    {
        return view('pockets.create');
    }

    /**
     * SIMPAN POCKET BARU
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'target' => 'nullable|numeric|min:0',
        ]);

        $pocket = Pocket::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'target' => $request->filled('target') ? $request->target : null,
            'balance' => 0,
        ]);

        return redirect()->route('pockets.show', $pocket->id)
            ->with('success', 'Pocket "'.$pocket->name.'" berhasil dibuat!');
    }

    /**
     * TAMPILKAN DETAIL POCKET + MUTASI
     */
    public function show(Pocket $pocket)
    {
        $this->authorizePocketOwner($pocket);

        $transactions = $pocket->transactions()->paginate(15);
        $targetProgress = $pocket->targetProgress();

        return view('pockets.show', compact('pocket', 'transactions', 'targetProgress'));
    }

    /**
     * TAMPILKAN FORM EDIT POCKET
     */
    public function edit(Pocket $pocket)
    {
        $this->authorizePocketOwner($pocket);

        return view('pockets.edit', compact('pocket'));
    }

    /**
     * UPDATE POCKET
     */
    public function update(Request $request, Pocket $pocket)
    {
        $this->authorizePocketOwner($pocket);

        $request->validate([
            'name' => 'required|string|max:255',
            'target' => 'nullable|numeric|min:0',
        ]);

        $pocket->update([
            'name' => $request->name,
            'target' => $request->filled('target') ? $request->target : null,
        ]);

        return redirect()->route('pockets.show', $pocket->id)
            ->with('success', 'Pocket berhasil diperbarui!');
    }

    /**
     * HAPUS POCKET
     * Hanya bisa dihapus jika saldo 0 dan tidak ada mutasi.
     */
    public function destroy(Pocket $pocket)
    {
        $this->authorizePocketOwner($pocket);

        if ($pocket->balance > 0) {
            return back()->with('error', 'Pocket tidak dapat dihapus karena masih memiliki saldo. Transfer dulu saldo-nya ke pocket lain atau tarik dulu.');
        }

        if ($pocket->transactions()->count() > 0) {
            return back()->with('error', 'Pocket tidak dapat dihapus karena sudah memiliki riwayat mutasi.');
        }

        $pocketName = $pocket->name;
        $pocket->delete();

        return redirect()->route('pockets.index')
            ->with('success', 'Pocket "'.$pocketName.'" berhasil dihapus!');
    }

    /**
     * Cek apakah user adalah pemilik pocket.
     */
    private function authorizePocketOwner(Pocket $pocket)
    {
        if ($pocket->user_id !== Auth::id()) {
            abort(403, 'Anda bukan pemilik pocket ini.');
        }
    }
}
