<?php

namespace App\Services;

use App\Enums\OrderType;
use App\Enums\OrderStatus;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class TradingService
{
    private WalletService $walletService;
    private GoldPriceService $goldPriceService;
    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        WalletService $walletService,
        GoldPriceService $goldPriceService,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->walletService = $walletService;
        $this->goldPriceService = $goldPriceService;
        $this->orderRepository = $orderRepository;
    }

    public function buyMetal(int $userId, string $metal, float $quantity): \App\Models\Order
    {
        return DB::transaction(function () use ($userId, $metal, $quantity) {
            $metal = strtolower($metal);
            $currentPrice = $this->goldPriceService->getCurrentPrice($metal);
            $totalPrice = $quantity * $currentPrice;

            // 1. Create PENDING Order
            $order = $this->orderRepository->create([
                'user_id' => $userId,
                'metal' => $metal,
                'type' => OrderType::BUY->value,
                'quantity' => $quantity,
                'price_per_unit' => $currentPrice,
                'total_price' => $totalPrice,
                'status' => OrderStatus::PENDING->value,
            ]);

            // 2. Deduct Fiat, Add Metal. (WalletService handles locking and balances)
            $this->walletService->withdrawFiat($userId, $totalPrice, "Bought {$quantity}g of " . ucfirst($metal));
            $this->walletService->addMetal($userId, $metal, $quantity);

            // 3. Mark COMPLETED
            $this->orderRepository->update($order->id, [
                'status' => OrderStatus::COMPLETED->value
            ]);
            
            $order->refresh();

            // Dispatch async job for receipt and notifications
            \App\Jobs\ProcessOrderReceipt::dispatch($order);

            return $order;
        });
    }

    public function sellMetal(int $userId, string $metal, float $quantity): \App\Models\Order
    {
        return DB::transaction(function () use ($userId, $metal, $quantity) {
            $metal = strtolower($metal);
            $currentPrice = $this->goldPriceService->getCurrentPrice($metal);
            $totalPrice = $quantity * $currentPrice;

            // 1. Create PENDING Order
            $order = $this->orderRepository->create([
                'user_id' => $userId,
                'metal' => $metal,
                'type' => OrderType::SELL->value,
                'quantity' => $quantity,
                'price_per_unit' => $currentPrice,
                'total_price' => $totalPrice,
                'status' => OrderStatus::PENDING->value,
            ]);

            // 2. Deduct Metal, Add Fiat. (WalletService handles locking and balances)
            $this->walletService->deductMetal($userId, $metal, $quantity);
            $this->walletService->depositFiat($userId, $totalPrice, "Sold {$quantity}g of " . ucfirst($metal));

            // 3. Mark COMPLETED
            $this->orderRepository->update($order->id, [
                'status' => OrderStatus::COMPLETED->value
            ]);
            
            $order->refresh();

            // Dispatch async job for receipt and notifications
            \App\Jobs\ProcessOrderReceipt::dispatch($order);

            return $order;
        });
    }

    public function buyGold(int $userId, float $quantity): \App\Models\Order
    {
        return $this->buyMetal($userId, 'gold', $quantity);
    }

    public function sellGold(int $userId, float $quantity): \App\Models\Order
    {
        return $this->sellMetal($userId, 'gold', $quantity);
    }
}
