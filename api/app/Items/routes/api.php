<?php

use App\Items\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])
    ->prefix('api/v1')
    ->group(function() {
        Route::group(['prefix' => 'establishments'], function() {
            Route::post('/{establishment_id}/menus/items/{id}/unlike', [ItemController::class, 'unlike']);
            Route::post('/{establishment_id}/menus/items/{id}/like', [ItemController::class, 'like']);
            Route::get('/{establishment_id}/menus/items/{id}', [ItemController::class, 'show']);
            Route::get('/{establishment_id}/menus/items', [ItemController::class, 'index']);
        });
    });
