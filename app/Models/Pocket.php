<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pocket extends Model
{
    use HasFactory;

    /**
     * Field yang diizinkan untuk diisi data (Mass Assignable)
     */
    protected $fillable = [
        'user_id',
        'name',
        'target', // opsional, nullable
        'balance',
    ];

    /**
     * Atribut yang di-cast
     */
    protected $casts = [
        'target' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    /**
     * RELASI TABEL USER (MANY-TO-ONE)
     * Pocket milik satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELASI TABEL POCKET TRANSACTIONS (ONE-TO-MANY)
     * Satu pocket memiliki banyak mutasi.
     */
    public function transactions()
    {
        return $this->hasMany(PocketTransaction::class)->orderBy('transaction_date', 'desc')->orderBy('id', 'desc');
    }

    /**
     * Hitung progres target dalam persen.
     * Return null jika target tidak di-set.
     */
    public function targetProgress()
    {
        if (!$this->target || $this->target <= 0) {
            return null;
        }

        return min(100, ($this->balance / $this->target) * 100);
    }

    /**
     * Apakah target sudah tercapai.
     */
    public function isTargetReached()
    {
        if (!$this->target || $this->target <= 0) {
            return null;
        }

        return $this->balance >= $this->target;
    }
}
