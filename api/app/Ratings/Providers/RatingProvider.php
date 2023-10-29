<?php

namespace App\Ratings\Providers;

use Illuminate\Support\ServiceProvider;

class RatingProvider extends ServiceProvider
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
