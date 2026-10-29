<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PocketTransaction extends Model
{
    use HasFactory;

    /**
     * Field yang diizinkan untuk diisi data (Mass Assignable)
     */
    protected $fillable = [
        'pocket_id',
        'type',
        'amount',
        'description',
        'transaction_date',
        'related_pocket_id',
        'related_group_id',
    ];

    /**
     * Atribut yang di-cast
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * RELASI TABEL POCKET (MANY-TO-ONE)
     * Setiap mutasi pocket milik satu pocket.
     */
    public function pocket()
    {
        return $this->belongsTo(Pocket::class);
    }

    /**
     * RELASI TABEL POCKET TUJUAN (MANY-TO-ONE)
     * Untuk transfer antar pocket.
     */
    public function relatedPocket()
    {
        return $this->belongsTo(Pocket::class, 'related_pocket_id');
    }

    /**
     * RELASI TABEL GROUP TUJUAN (MANY-TO-ONE)
     * Untuk transfer ke tabungan bersama.
     */
    public function relatedGroup()
    {
        return $this->belongsTo(Group::class, 'related_group_id');
    }

    /**
     * Label Bahasa Indonesia untuk tipe mutasi.
     */
    public function typeLabel()
    {
        return match ($this->type) {
            'top_up' => 'Setoran',
            'withdraw' => 'Penarikan',
            'transfer_in' => 'Transfer Masuk',
            'transfer_out' => 'Transfer Keluar',
            default => $this->type,
        };
    }
}
