<?php

use App\Establishments\Http\Controllers\EstablishmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'throttle:rate-limiter-api'])
    ->prefix('api/v1')
    ->group(function() {
       Route::apiResource('establishments', EstablishmentController::class);
    });