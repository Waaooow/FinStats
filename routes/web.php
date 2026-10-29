<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\PocketController;
use App\Http\Controllers\PocketTransactionController;
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

    // Pocket Tabungan Pribadi
    Route::prefix('pockets')->name('pockets.')->group(function () {
        Route::get('/', [PocketController::class, 'index'])->name('index');
        Route::get('/create', [PocketController::class, 'create'])->name('create');
        Route::post('/', [PocketController::class, 'store'])->name('store');
        Route::get('/{pocket}', [PocketController::class, 'show'])->name('show');
        Route::get('/{pocket}/edit', [PocketController::class, 'edit'])->name('edit');
        Route::patch('/{pocket}', [PocketController::class, 'update'])->name('update');
        Route::delete('/{pocket}', [PocketController::class, 'destroy'])->name('destroy');

        // Mutasi pocket (top up, withdraw, transfer)
        Route::post('/{pocket}/top-up', [PocketTransactionController::class, 'topUp'])->name('topUp');
        Route::post('/{pocket}/withdraw', [PocketTransactionController::class, 'withdraw'])->name('withdraw');
        Route::post('/transfer', [PocketTransactionController::class, 'transfer'])->name('transfer');
    });
});

// Manajemen Profil (Harus Login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// EDIT OR DELETE TRANSACTION
Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

// Breeze Auth Routes (Login, Register, Logout)
require __DIR__.'/auth.php';
