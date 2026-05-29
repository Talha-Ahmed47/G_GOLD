<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\{AdminMaterialController, AuthController, DashboardController, HomeController};
use App\Http\Controllers\Api\WalletController;
use App\Services\GoldPriceService;

Route::get('/', [HomeController::class, 'index'])->name('home');
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


Route::get('/auth/google', [AuthController::class, 'redirectGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogle']);

Route::get('/wallet/jazzcash/payment', [WalletController::class, 'jazzcashPayment'])->name('jazzcash.payment');
Route::post('/wallet/jazzcash/callback', [WalletController::class, 'jazzcashCallback']);

Route::get('/wallet/stripe/payment', [WalletController::class, 'stripePayment'])->name('stripe.payment');
Route::get('/wallet/stripe/success', [WalletController::class, 'stripeSuccess'])->name('stripe.success');
Route::get('/wallet/stripe/cancel', [WalletController::class, 'stripeCancel'])->name('stripe.cancel');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/wallet/deposit', [DashboardController::class, 'deposit']);
    Route::post('/trade/buy', [DashboardController::class, 'buy']);
    Route::post('/trade/sell', [DashboardController::class, 'sell']);
    Route::post('/settings/update', [DashboardController::class, 'updateSettings']);
    Route::post('/settings/toggle-status', [DashboardController::class, 'toggleStatus']);
    Route::post('/vault/pay-invoice', [DashboardController::class, 'payVaultInvoice']);
    Route::post('/admin/update-settings', [DashboardController::class, 'updateAdminSettings']);
    // Admin Materials CRUD
    Route::get('/admin/materials', [AdminMaterialController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.materials.index');
    // Admin Materials CRUD routes
    Route::post('/admin/materials', [AdminMaterialController::class, 'store'])->middleware(['auth', 'admin'])->name('admin.materials.store');
    Route::put('/admin/materials/{id}', [AdminMaterialController::class, 'update'])->middleware(['auth', 'admin'])->name('admin.materials.update');
    Route::delete('/admin/materials/{id}', [AdminMaterialController::class, 'destroy'])->middleware(['auth', 'admin'])->name('admin.materials.destroy');



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
