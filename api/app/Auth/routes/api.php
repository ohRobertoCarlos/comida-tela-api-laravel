<?php

use App\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])
    ->prefix('api/v1/auth')
    ->group(function() {
        Route::middleware(['auth:api'])->group(function() {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);

            Route::post('/email/verification-notification/{id}', [\App\Auth\Http\Controllers\VerifyEmailController::class, 'resendEmaiVerificationUser'])
                ->middleware(['throttle:6,1', 'verified']);

            Route::post('/email/verification-notification', [\App\Auth\Http\Controllers\VerifyEmailController::class, 'resendEmaiVerification'])
                ->middleware(['throttle:6,1'])
                ->name('verification.send');
        });

        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::get('/email/verify/{id}/{hash}', [\App\Auth\Http\Controllers\VerifyEmailController::class, 'verifyEmail'])
            ->middleware(['signed'])
            ->name('verification.verify');

        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
            ->middleware('guest')
            ->name('password.email');

        Route::post('/reset-password', [AuthController::class, 'resetPassword'])
            ->middleware('guest')
            ->name('password.update');
    });
