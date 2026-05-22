<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Halaman awal saat web pertama kali dibuka
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute Dashboard Keuangan kita (Mengambil data lewat Controller)
Route::get('/dashboard', [TransactionController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rute untuk memproses input transaksi baru
Route::post('/transactions', [TransactionController::class, 'store'])
    ->middleware(['auth'])
    ->name('transactions.store');

// Rute manajemen Profile bawaan Laravel Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/transactions/export', [TransactionController::class, 'export'])
    ->middleware(['auth'])
    ->name('transactions.export');

Route::middleware(['auth', 'verified'])->group(function () {
    // Route Utama Transaksi Personal kamu yang sudah ada...
    Route::get('/dashboard', [TransactionController::class, 'index'])->name('dashboard');

    // =========================================================================
    // JALUR ROUTE BARU: SISTEM TABUNGAN BERSAMA (KELOMPOK)
    // =========================================================================
    Route::get('/group-savings', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/group-savings', [GroupController::class, 'store'])->name('groups.store');
    Route::post('/group-savings/join', [GroupController::class, 'join'])->name('groups.join');
    Route::get('/group-savings/{id}', [GroupController::class, 'show'])->name('groups.show');
    Route::post('/group-savings/{id}/transaction', [GroupController::class, 'storeTransaction'])->name('groups.transaction.store');
});

// PENTING: Baris ini bertugas memuat rute Login, Register, dan Log Out milik Breeze.
// Jangan sampai baris ini hilang dari paling bawah file!
require __DIR__.'/auth.php';
