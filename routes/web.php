<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WithdrawController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
 */

// Redirect root to login
Route::get('/', fn() => redirect()->route('login'));

// ----- Auth Routes -----
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ----- Withdraw Routes (require auth) -----
Route::middleware('auth')->group(function () {
    Route::get('/withdraw', [WithdrawController::class, 'index'])->name('withdraw.index');
    Route::get('/api/withdraw/data', [WithdrawController::class, 'api'])->name('withdraw.api');
    Route::post('/api/withdraw/auto', [WithdrawController::class, 'autoWithdraw'])->name('withdraw.auto');
    Route::post('/api/withdraw/manual', [WithdrawController::class, 'manualWithdraw'])->name('withdraw.manual');
    Route::post('/api/withdraw/cancel', [WithdrawController::class, 'cancelWithdraw'])->name('withdraw.cancel');
});
