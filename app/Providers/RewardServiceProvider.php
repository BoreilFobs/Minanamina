<?php

namespace App\Providers;

use App\Services\BadgeService;
use App\Services\RewardService;
use Illuminate\Support\ServiceProvider;

class RewardServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register BadgeService as singleton first
        $this->app->singleton(BadgeService::class, function ($app) {
            return new BadgeService();
        });

        // Register RewardService with BadgeService dependency
        $this->app->singleton(RewardService::class, function ($app) {
            return new RewardService($app->make(BadgeService::class));
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
