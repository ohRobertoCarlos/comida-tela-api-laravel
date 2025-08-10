<?php

namespace App\Establishments\Providers;

use App\Establishments\Events\EstablishmentUpdated;
use App\Establishments\Listeners\UpdateQrCode;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        EstablishmentUpdated::class => [
            UpdateQrCode::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
