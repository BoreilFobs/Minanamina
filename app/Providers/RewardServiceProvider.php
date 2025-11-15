<?php

namespace App\Providers;

use App\Services\RewardService;
use Illuminate\Support\ServiceProvider;

class RewardServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(RewardService::class, function ($app) {
            return new RewardService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
