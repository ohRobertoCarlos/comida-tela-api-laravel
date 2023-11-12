<?php

use App\Categories\Http\Controllers\CategoryController;
use App\Http\Middleware\UserIsEstablishment;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth-api', UserIsEstablishment::class])
    ->prefix('api/v1')
    ->group(function() {
       Route::apiResource('establishments/{establishment_id}/categories', CategoryController::class);
    });
