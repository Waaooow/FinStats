<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Halaman Landing Page Utama
Route::get('/', function () {
    return view('welcome');
});

// Fitur Utama (Harus Login & Terverifikasi)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Keuangan Utama
    Route::get('/dashboard', [TransactionController::class, 'index'])->name('dashboard');

    // Transaksi Personal
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');

    // Sistem Tabungan Bersama (Kelompok)
    Route::prefix('group-savings')->name('groups.')->group(function () {
        Route::get('/', [GroupController::class, 'index'])->name('index'); 
        Route::post('/', [GroupController::class, 'store'])->name('store');
        Route::post('/join', [GroupController::class, 'join'])->name('join');
        Route::get('/{id}', [GroupController::class, 'show'])->name('show');
        Route::post('/{id}/transaction', [GroupController::class, 'storeTransaction'])->name('transaction.store');
    });
});

// Manajemen Profil (Harus Login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze Auth Routes (Login, Register, Logout)
require __DIR__.'/auth.php';