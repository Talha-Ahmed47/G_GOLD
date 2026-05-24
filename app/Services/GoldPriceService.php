<?php

namespace App\Services;

use App\Models\GoldPrice;
use App\Events\GoldPriceUpdated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GoldPriceService
{
    private const CACHE_TTL = 60; // 1 minute

    /**
     * Fetch the live price for all metals, update DB, broadcast event, and cache.
     */
    public function updateLivePrice(): array
    {
        $metals = ['gold' => 7200, 'silver' => 31, 'platinum' => 1000, 'palladium' => 950];
        $livePrices = [];

        foreach ($metals as $metal => $basePrice) {
            try {
                // Mocking an external API call for each metal
                $livePrice = round(rand($basePrice - 50, $basePrice + 50) + (rand(0, 99) / 100), 4);
                if ($metal === 'silver') {
                    $livePrice = round(rand(28, 35) + (rand(0, 99) / 100), 4);
                }
            } catch (\Exception $e) {
                $livePrice = $this->getCurrentPrice($metal);
            }

            GoldPrice::create([
                'metal' => $metal,
                'price_per_gram' => $livePrice,
                'currency' => 'USD',
                'fetched_at' => now(),
            ]);

            Cache::put("current_{$metal}_price", $livePrice, self::CACHE_TTL);
            $livePrices[$metal] = $livePrice;
        }

        broadcast(new GoldPriceUpdated($livePrices['gold']));
        return $livePrices;
    }

    /**
     * Get the current price from Cache. Fast read for Trading Engine.
     */
    public function getCurrentPrice(string $metal = 'gold'): float
    {
        return Cache::remember("current_{$metal}_price", self::CACHE_TTL, function () use ($metal) {
            $latest = GoldPrice::where('metal', $metal)->latest('fetched_at')->first();
            if ($latest) {
                return (float) $latest->price_per_gram;
            }

            // Return correct live base fallback prices aligned with updateLivePrice
            $fallbacks = [
                'gold' => 7200.00,
                'silver' => 31.00,
                'platinum' => 1000.00,
                'palladium' => 950.00,
            ];
            return $fallbacks[$metal] ?? 1.00;
        });
    }

    public function getAllPrices(): array
    {
        // Proactively initialize database with live prices if it is empty to ensure complete parity across website and dashboard
        if (GoldPrice::count() === 0) {
            return $this->updateLivePrice();
        }

        return [
            'gold' => $this->getCurrentPrice('gold'),
            'silver' => $this->getCurrentPrice('silver'),
            'platinum' => $this->getCurrentPrice('platinum'),
            'palladium' => $this->getCurrentPrice('palladium'),
        ];
    }
}
