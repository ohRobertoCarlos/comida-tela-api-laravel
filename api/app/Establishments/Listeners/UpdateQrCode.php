<?php

namespace App\Establishments\Listeners;

use App\Establishments\Events\EstablishmentUpdated;
use App\Establishments\Services\EstablishmentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateQrCode
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EstablishmentUpdated $event): void
    {
        $establishmentService = resolve(EstablishmentService::class);
        $establishmentService->updateQrCode($event->id);
    }
}
