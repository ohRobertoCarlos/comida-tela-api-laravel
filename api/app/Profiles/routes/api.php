<?php

use App\Profiles\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth-api'])
    ->prefix('api/v1')
    ->group(function() {
       Route::patch('establishments/{establishment_id}/profiles', [ProfileController::class, 'update']);
    });
