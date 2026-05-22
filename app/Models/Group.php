<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    // Menghubungkan grup ke anggota-anggotanya
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // Menghubungkan grup ke semua transaksi yang ada di dalamnya
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
