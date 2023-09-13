<?php

use App\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:rate-limiter-api'])
    ->prefix('v1/auth')
    ->group(function() {
        Route::middleware(['auth:api'])->group(function() {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::get('/me', [AuthController::class, 'me']);
        });

        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });