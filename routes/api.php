<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Web\HomeController;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::get('/gold-price', [HomeController::class, 'getGoldPrice']);
Route::get('/wallet/jazzcash/payment', [WalletController::class, 'jazzcashPayment']);
Route::post('/wallet/jazzcash/callback', [WalletController::class, 'jazzcashCallback']);

Route::middleware('auth:sanctum')->group(function () {
    // User Wallet
    Route::get('/wallet/balance', [WalletController::class, 'balance']);
    Route::post('/wallet/deposit', [WalletController::class, 'deposit']);
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw']);



    // User Orders
    Route::post('/trade/buy', [OrderController::class, 'buy']);
    Route::post('/trade/sell', [OrderController::class, 'sell']);

    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});
