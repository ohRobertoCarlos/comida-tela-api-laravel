<?php

namespace App\Establishments\Providers;

use Illuminate\Support\ServiceProvider;

class EstablishmentProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}