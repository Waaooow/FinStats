<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Field yang diizinkan untuk diisi data (Mass Assignable)
     */
    protected $fillable = [
        'user_id',
        'group_id', // KOREKSI: Wajib ditambahkan agar kolom group_id bisa menyimpan data
        'type',
        'amount',
        'description',
        'date',
    ];

    /**
     * =========================================================================
     * RELASI TABEL USER (MANY-TO-ONE)
     * Menghubungkan transaksi ini kembali ke user yang membuatnya.
     * =========================================================================
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * =========================================================================
     * RELASI TABEL GROUP (MANY-TO-ONE)
     * Menghubungkan transaksi ini ke grup tabungan terkait (jika ada).
     * =========================================================================
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
