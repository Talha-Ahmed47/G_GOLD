<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\WalletRepositoryInterface::class,
            \App\Repositories\Eloquent\WalletRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\TransactionRepositoryInterface::class,
            \App\Repositories\Eloquent\TransactionRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\OrderRepositoryInterface::class,
            \App\Repositories\Eloquent\OrderRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
