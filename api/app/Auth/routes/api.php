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
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::get('/email/verify', function () {
            return view('auth.verify-email');
        })
            ->middleware('auth')
            ->name('verification.notice');

        Route::get('/email/verify/{id}/{hash}', [\App\Auth\Http\Controllers\VerifyEmailController::class, 'verifyEmail'])
            ->middleware(['signed'])
            ->name('verification.verify');
    });
