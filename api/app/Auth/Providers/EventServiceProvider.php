<?php

namespace App\Auth\Providers;

use App\Auth\Observers\UserObserver;
use App\Models\User;

class EventServiceProvider extends \Illuminate\Foundation\Support\Providers\EventServiceProvider
{
    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
    }
}
