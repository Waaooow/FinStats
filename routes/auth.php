<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route; // Tambahan untuk memanggil API

Route::middleware('guest')->group(function () {

    // --- CUSTOM: GET Register dengan API Kurs ---
    Route::get('register', function () {
        try {
            $response = Http::timeout(3)->get('https://open.er-api.com/v6/latest/USD')->json();
            $kurs = $response['rates']['IDR'] ?? 16450;
        } catch (Exception $e) {
            $kurs = 16450; // Fallback jika API down/offline
        }

        return view('auth.register', ['kurs' => $kurs]);
    })->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    // --- CUSTOM: GET Login dengan API Kurs ---
    Route::get('login', function () {
        try {
            $response = Http::timeout(3)->get('https://open.er-api.com/v6/latest/USD')->json();
            $kurs = $response['rates']['IDR'] ?? 16450;
        } catch (Exception $e) {
            $kurs = 16450; // Fallback jika API down/offline
        }

        return view('auth.login', ['kurs' => $kurs]);
    })->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // --- Rute Bawaan Laravel Breeze Lainnya ---
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
