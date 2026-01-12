<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GuestSessionService;

class GuestSessionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(GuestSessionService::class, function ($app) {
            return new GuestSessionService();
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
