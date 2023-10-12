<?php

use App\Menus\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth-api'])
    ->prefix('api/v1')
    ->group(function() {
       Route::get('establishments/{establishment_id}/menu', [MenuController::class, 'show']);

       Route::post('establishments/{establishment_id}/menu/items', [MenuController::class, 'addItem']);
       Route::patch('establishments/{establishment_id}/menu/items/{item_id}', [MenuController::class, 'updateItem']);
       Route::delete('establishments/{establishment_id}/menu/items/{item_id}', [MenuController::class, 'deleteItem']);
    });
