<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchLiveGoldPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gold:fetch-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches live gold price and caches it';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\GoldPriceService $goldPriceService)
    {
        $this->info('Fetching live gold price...');
        $price = $goldPriceService->updateLivePrice();
        $this->info("Gold price updated: \${$price} per gram.");
    }
}
