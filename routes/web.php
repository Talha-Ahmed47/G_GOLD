<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Services\GoldPriceService;
use App\Http\Controllers\Web\AuthController;


use App\Http\Controllers\Web\HomeController;

Route::get('/', [HomeController::class, 'index']);
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/debug-jazz', function() {
    $data = [
        'pp_Version' => '1.1',
        'pp_TxnType' => 'MWALLET',
        'pp_Amount' => 1000,
        'pp_TxnRefNo' => 'TEST123',
    ];

    return view('jazzcash.redirect', compact('data'));
});

Route::get('/wallet/jazzcash/payment', [\App\Http\Controllers\Api\WalletController::class, 'jazzcashPayment']);
Route::post('/wallet/jazzcash/callback', [\App\Http\Controllers\Api\WalletController::class, 'jazzcashCallback']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/wallet/deposit', [DashboardController::class, 'deposit']);
    Route::post('/trade/buy', [DashboardController::class, 'buy']);
    Route::post('/trade/sell', [DashboardController::class, 'sell']);
    Route::post('/settings/update', [DashboardController::class, 'updateSettings']);
    Route::post('/settings/toggle-status', [DashboardController::class, 'toggleStatus']);
    Route::post('/vault/pay-invoice', [DashboardController::class, 'payVaultInvoice']);
    Route::post('/admin/update-settings', [DashboardController::class, 'updateAdminSettings']);




});

Route::get('/cron/fetch-gold-price', function (GoldPriceService $goldPriceService) {
    // You can add a simple token check here to prevent abuse
    // if (request('token') !== config('services.cron.token')) { abort(403); }

    $price = $goldPriceService->updateLivePrice();
    return response()->json([
        'success' => true,
        'message' => 'Gold price updated successfully via Cron',
        'price' => $price,
    ]);
});
