<?php

use App\Ratings\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])
    ->prefix('api/v1')
    ->group(function() {
       Route::post('establishments/{establishment_id}/ratings', [RatingController::class, 'store']);
    });
