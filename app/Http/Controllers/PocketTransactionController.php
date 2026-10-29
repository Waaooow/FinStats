<?php

namespace App\Http\Controllers;

use App\Models\Pocket;
use App\Models\PocketTransaction;
use App\Models\Group;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PocketTransactionController extends Controller
{
    /**
     * TOP UP POCKET (setoran uang ke pocket)
     */
    public function topUp(Request $request, Pocket $pocket)
    {
        $this->authorizePocketOwner($pocket);

        $request->validate([
            'amount' => 'required|numeric|min:100',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date|before_or_equal:today',
        ]);

        DB::beginTransaction();
        try {
            $pocket->increment('balance', $request->amount);

            PocketTransaction::create([
                'pocket_id' => $pocket->id,
                'type' => 'top_up',
                'amount' => $request->amount,
                'description' => $request->description ?? 'Setoran ke pocket',
                'transaction_date' => $request->date,
            ]);

            // Catat pula di transaksi harian pribadi (requirement 4)
            Transaction::create([
                'user_id' => Auth::id(),
                'group_id' => null,
                'type' => 'outcome', // uang keluar dari saldo harian ke pocket
                'amount' => $request->amount,
                'description' => 'Top up ke pocket: '.$pocket->name,
                'date' => $request->date,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal melakukan setoran: '.$e->getMessage());
        }

        return redirect()->route('pockets.show', $pocket->id)
            ->with('success', 'Setoran Rp '.number_format($request->amount, 0, ',', '.').' ke pocket "'.$pocket->name.'" berhasil!');
    }

    /**
     * WITHDRAW DARI POCKET (tarik uang dari pocket)
     */
    public function withdraw(Request $request, Pocket $pocket)
    {
        $this->authorizePocketOwner($pocket);

        $request->validate([
            'amount' => 'required|numeric|min:100',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date|before_or_equal:today',
        ]);

        if ($pocket->balance < $request->amount) {
            return back()->with('error', 'Saldo pocket tidak mencukupi untuk penarikan.');
        }

        DB::beginTransaction();
        try {
            $pocket->decrement('balance', $request->amount);

            PocketTransaction::create([
                'pocket_id' => $pocket->id,
                'type' => 'withdraw',
                'amount' => $request->amount,
                'description' => $request->description ?? 'Penarikan dari pocket',
                'transaction_date' => $request->date,
            ]);

            // Catat pula di transaksi harian pribadi
            Transaction::create([
                'user_id' => Auth::id(),
                'group_id' => null,
                'type' => 'income', // uang masuk dari pocket ke saldo harian
                'amount' => $request->amount,
                'description' => 'Penarikan dari pocket: '.$pocket->name,
                'date' => $request->date,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal melakukan penarikan: '.$e->getMessage());
        }

        return redirect()->route('pockets.show', $pocket->id)
            ->with('success', 'Penarikan Rp '.number_format($request->amount, 0, ',', '.').' dari pocket "'.$pocket->name.'" berhasil!');
    }

    /**
     * TRANSFER ANTAR POCKET ATAU KE TABUNGAN BERSAMA
     */
    public function transfer(Request $request)
    {
        $request->validate([
            'from_pocket_id' => 'required|exists:pockets,id',
            'amount' => 'required|numeric|min:100',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date|before_or_equal:today',
            'to_type' => 'required|in:pocket,group',
            'to_pocket_id' => 'required_if:to_type,pocket|exists:pockets,id',
            'to_group_id' => 'required_if:to_type,group|exists:groups,id',
        ]);

        $fromPocket = Pocket::findOrFail($request->from_pocket_id);
        $this->authorizePocketOwner($fromPocket);

        if ($fromPocket->balance < $request->amount) {
            return back()->with('error', 'Saldo pocket asal tidak mencukupi.');
        }

        // Cek kepemilikan pocket tujuan jika transfer antar pocket
        if ($request->to_type === 'pocket') {
            $toPocket = Pocket::findOrFail($request->to_pocket_id);
            if ($toPocket->user_id !== Auth::id()) {
                return back()->with('error', 'Pocket tujuan bukan milik Anda.');
            }
        }

        // Cek keanggotaan grup jika transfer ke tabungan bersama
        if ($request->to_type === 'group') {
            $group = Group::findOrFail($request->to_group_id);
            if (!$group->users->contains(Auth::id())) {
                return back()->with('error', 'Anda bukan anggota kelompok tabungan ini.');
            }
        }

        DB::beginTransaction();
        try {
            // Kurangi saldo pocket asal
            $fromPocket->decrement('balance', $request->amount);

            // Buat mutasi transfer_out di pocket asal
            PocketTransaction::create([
                'pocket_id' => $fromPocket->id,
                'type' => 'transfer_out',
                'amount' => $request->amount,
                'description' => $request->description ?? 'Transfer keluar dari pocket '.$fromPocket->name,
                'transaction_date' => $request->date,
                'related_pocket_id' => $request->to_type === 'pocket' ? $request->to_pocket_id : null,
                'related_group_id' => $request->to_type === 'group' ? $request->to_group_id : null,
            ]);

            if ($request->to_type === 'pocket') {
                // Tambah saldo pocket tujuan
                $toPocket->increment('balance', $request->amount);

                PocketTransaction::create([
                    'pocket_id' => $toPocket->id,
                    'type' => 'transfer_in',
                    'amount' => $request->amount,
                    'description' => $request->description ?? 'Transfer masuk dari pocket '.$fromPocket->name,
                    'transaction_date' => $request->date,
                    'related_pocket_id' => $fromPocket->id,
                ]);
            } else {
                // Transfer ke tabungan bersama - catat di transaksi grup
                $group = Group::findOrFail($request->to_group_id);

                \App\Models\Transaction::create([
                    'user_id' => Auth::id(),
                    'group_id' => $group->id,
                    'type' => 'income',
                    'amount' => $request->amount,
                    'description' => $request->description ?? 'Transfer dari pocket '.$fromPocket->name,
                    'date' => $request->date,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal melakukan transfer: '.$e->getMessage());
        }

        $tujuanLabel = $request->to_type === 'pocket'
            ? 'pocket "'.Pocket::find($request->to_pocket_id)->name.'"'
            : 'tabungan bersama "'.Group::find($request->to_group_id)->name.'"';

        return redirect()->route('pockets.show', $fromPocket->id)
            ->with('success', 'Transfer Rp '.number_format($request->amount, 0, ',', '.').' ke '.$tujuanLabel.' berhasil!');
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
