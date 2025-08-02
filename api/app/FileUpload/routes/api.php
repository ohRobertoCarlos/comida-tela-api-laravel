<?php

use App\FileUpload\Http\Controllers\UploadFileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth-api'])
    ->prefix('api/v1')
    ->group(function() {
        Route::group(['prefix' => 'files/public'], function() {
            Route::post('/', [UploadFileController::class, 'uploadPublic']);
        });
    });
