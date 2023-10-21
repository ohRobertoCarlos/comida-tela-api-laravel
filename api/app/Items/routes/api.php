<?php

use App\Items\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])
    ->prefix('api/v1')
    ->group(function() {
        Route::group(['prefix' => 'establishments'], function() {
            Route::get('/{establishment_id}/menus/items', [ItemController::class, 'index']);
            Route::get('/{establishment_id}/menus/items/{id}', [ItemController::class, 'show']);
        });
    });
