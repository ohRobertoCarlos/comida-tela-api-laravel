<?php

use App\Establishments\Http\Controllers\EstablishmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')
    ->group(function() {
        Route::middleware(['auth-api'])
            ->group(function() {
                Route::apiResource('establishments', EstablishmentController::class)->except('index');
                Route::post('establishments/{establishment_id}/users', [EstablishmentController::class, 'createUser']);
                Route::get('establishments/{establishment_id}/users', [EstablishmentController::class, 'getUsers']);
                Route::get('establishments/{establishment_id}/users/{user_id}', [EstablishmentController::class, 'getUser']);
                Route::patch('establishments/{establishment_id}/users/{user_id}', [EstablishmentController::class, 'updateUser']);
                Route::delete('establishments/{establishment_id}/users/{user_id}', [EstablishmentController::class, 'deleteUser']);
            });

        Route::middleware(['api'])
            ->group(function() {
                Route::get('establishments/menuCode/{menu_code}', [EstablishmentController::class, 'showByMenuCode']);
                Route::get('establishments', [EstablishmentController::class, 'index']);
            });
    });
