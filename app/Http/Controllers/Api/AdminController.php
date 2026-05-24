<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Order;
use App\Enums\Role;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Simple authorization check
        if ($request->user()->role !== Role::ADMIN->value) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $totalUsers = User::count();
        $totalFiatLiquidity = Wallet::sum('balance');
        $totalGoldHoldings = Wallet::sum('gold_balance');
        $totalOrdersCompleted = Order::where('status', \App\Enums\OrderStatus::COMPLETED->value)->count();

        return response()->json([
            'platform_stats' => [
                'total_users' => $totalUsers,
                'total_fiat_liquidity' => $totalFiatLiquidity,
                'total_gold_holdings' => $totalGoldHoldings,
                'total_completed_trades' => $totalOrdersCompleted,
            ]
        ]);
    }
}
