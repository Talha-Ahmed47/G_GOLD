<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Services\{AdminMaterialService, GoldPriceService, TradingService, WalletService};

class DashboardController extends Controller
{
    protected WalletService $walletService;
    protected TradingService $tradingService;
    protected GoldPriceService $goldPriceService;

    public function __construct(
        WalletService $walletService,
        TradingService $tradingService,
        GoldPriceService $goldPriceService
    ) {
        $this->walletService = $walletService;
        $this->tradingService = $tradingService;
        $this->goldPriceService = $goldPriceService;
    }

    public function index()
    {

        $wallet = Auth::user()->wallet()->firstOrCreate([]);
        $walletDeposit = Auth::user()->walletDeposit()->firstOrCreate([], ['amount' => 0]);
        $fiatBalance = $walletDeposit ? $walletDeposit->amount : 0;
        $metalPrices = $this->goldPriceService->getAllPrices();
        
        // Fetch last 7 historical prices for each metal
        $chartData = [];
        foreach (['gold', 'silver', 'platinum', 'palladium'] as $metal) {
            $history = \App\Models\GoldPrice::where('metal', $metal)
                ->latest('fetched_at')
                ->limit(7)
                ->get()
                ->reverse();
                
            $prices = $history->pluck('price_per_gram')->toArray();
            
            // Fallback if DB is empty
            if (empty($prices)) {
                $base = ['gold' => 7200, 'silver' => 31, 'platinum' => 1000, 'palladium' => 950][$metal];
                for ($i = 0; $i < 7; $i++) {
                    $prices[] = $base + rand(-20, 20);
                }
            }
            $chartData[$metal] = $prices;
        }

        // Fetch global system settings
        $shopLocation = \App\Models\SystemSetting::getVal('shop_location', 'Aurum Gold Store, 123 Luxury Lane, Midtown Manhattan, NY');
        $storageLimitKg = (float) \App\Models\SystemSetting::getVal('storage_limit_kg', '5.0');
        $extraStoragePrice = (float) \App\Models\SystemSetting::getVal('extra_storage_price_per_kg', '15.00');

        // Fetch admin inventory if current user is admin
        $adminInventory = null;
        $customers = collect();
        $purchasedHoldings = [];
        $adminMaterials = collect();
        if (Auth::user()->hasRole('admin')) {
            $adminUser = \App\Models\User::where('role', 'admin')->first();
        
            $adminInventory = $adminUser ? $adminUser->wallet : null;
            $customers = \App\Models\User::where('role', 'user')->with(['wallet', 'walletDeposit'])->get();
            $purchasedHoldings = [
                'gold' => \App\Models\Wallet::where('user_id', '!=', Auth::id())->sum('gold_balance'),
                'silver' => \App\Models\Wallet::where('user_id', '!=', Auth::id())->sum('silver_balance'),
                'platinum' => \App\Models\Wallet::where('user_id', '!=', Auth::id())->sum('platinum_balance'),
                'palladium' => \App\Models\Wallet::where('user_id', '!=', Auth::id())->sum('palladium_balance'),
            ];
            $adminMaterials = \App\Models\AdminMaterial::all();
            // dd($customers);    
        }
       
        $totalWeightG = $wallet->gold_balance + $wallet->silver_balance + $wallet->platinum_balance + $wallet->palladium_balance;
        $totalWeightKg = $totalWeightG / 1000;
        $extraWeightKg = max(0, $totalWeightKg - $storageLimitKg);
        $monthlyExtraFee = $extraWeightKg * $extraStoragePrice;

        $userAddress = Auth::user()->address ?? '';

        return view('dashboard.index', compact(
            'wallet',
            'fiatBalance',
            'metalPrices',
            'chartData',
            'shopLocation',
            'storageLimitKg',
            'extraStoragePrice',
            'totalWeightKg',
            'extraWeightKg',
            'monthlyExtraFee',
            'userAddress',
            'customers',
            'purchasedHoldings',
            'adminMaterials'
        ));
    }

    public function deposit(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:0.01']);
        
        $this->walletService->depositFiat(Auth::id(), $request->amount);
        
        $wallet        = Auth::user()->wallet->fresh();
        $walletDeposit = Auth::user()->walletDeposit->fresh();

        return response()->json([
            'success'           => true,
            'fiat_balance'      => $walletDeposit ? $walletDeposit->amount : 0,
            'gold_balance'      => $wallet->gold_balance,
            'silver_balance'    => $wallet->silver_balance,
            'platinum_balance'  => $wallet->platinum_balance,
            'palladium_balance' => $wallet->palladium_balance
        ]);
    }

    public function buy(Request $request)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.0001',
            'metal'    => 'required|string|in:gold,silver,platinum,palladium'
        ]);
        
        $metal = strtolower($request->metal);
        $this->tradingService->buyMetal(Auth::id(), $metal, $request->quantity);
        
        $wallet        = Auth::user()->wallet->fresh();
        $walletDeposit = Auth::user()->walletDeposit->fresh();

        return response()->json([
            'success'           => true,
            'message'           => 'Successfully bought ' . $request->quantity . 'g of ' . ucfirst($metal) . '!',
            'fiat_balance'      => $walletDeposit ? $walletDeposit->amount : 0,
            'gold_balance'      => $wallet->gold_balance,
            'silver_balance'    => $wallet->silver_balance,
            'platinum_balance'  => $wallet->platinum_balance,
            'palladium_balance' => $wallet->palladium_balance
        ]);
    }

    public function sell(Request $request)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.0001',
            'metal' => 'required|string|in:gold,silver,platinum,palladium'
        ]);
        
        $metal = strtolower($request->metal);
        $this->tradingService->sellMetal(Auth::id(), $metal, $request->quantity);
        
        $wallet = Auth::user()->wallet->fresh();
        $walletDeposit = Auth::user()->walletDeposit->fresh();
        return response()->json([
            'success' => true,
            'message' => 'Successfully sold ' . $request->quantity . 'g of ' . ucfirst($metal) . '!',
            'fiat_balance' => $walletDeposit ? $walletDeposit->amount : 0,
            'gold_balance' => $wallet->gold_balance,
            'silver_balance' => $wallet->silver_balance,
            'platinum_balance' => $wallet->platinum_balance,
            'palladium_balance' => $wallet->palladium_balance
        ]);
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'address'       => 'nullable|string|max:500',
            'current_password'        => 'nullable|string',
            'new_password'            => 'nullable|string|min:6|confirmed',
        ]);

        $user->email   = $request->email;
        $user->address = $request->address;

        if ($request->filled('new_password')) {
            if (!$request->filled('current_password') || !\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The current password provided is incorrect.',
                ], 422);
            }
            $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile settings updated successfully!',
            'email'   => $user->email,
            'address' => $user->address,
        ]);
    }

    public function toggleStatus(Request $request)
    {
        $user = Auth::user();
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';

        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'success' => true,
            'message' => 'Your account has been successfully ' . $status . '.',
            'is_active' => $user->is_active
        ]);
    }

    public function payVaultInvoice(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:0.01']);
        
        try {
            $this->walletService->withdrawFiat(Auth::id(), $request->amount, 'Vault storage service fee');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
        
        $wallet = Auth::user()->wallet->fresh();
        $walletDeposit = Auth::user()->walletDeposit->fresh();
        return response()->json([
            'success' => true,
            'fiat_balance' => $walletDeposit ? $walletDeposit->amount : 0,
            'gold_balance' => $wallet->gold_balance,
            'silver_balance' => $wallet->silver_balance,
            'platinum_balance' => $wallet->platinum_balance,
            'palladium_balance' => $wallet->palladium_balance
        ]);
    }

    public function updateAdminSettings(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized admin action.'], 403);
        }

        $request->validate([
            'shop_location' => 'required|string',
            'storage_limit_kg' => 'required|numeric|min:0.1',
            'extra_storage_price_per_kg' => 'required|numeric|min:0'
        ]);

        \App\Models\SystemSetting::setVal('shop_location', $request->shop_location);
        \App\Models\SystemSetting::setVal('storage_limit_kg', $request->storage_limit_kg);
        \App\Models\SystemSetting::setVal('extra_storage_price_per_kg', $request->extra_storage_price_per_kg);

        return response()->json([
            'success' => true,
            'message' => 'Global system settings updated successfully!'
        ]);
    }
}
