<?php

use App\Menus\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/redirect/menus', [MenuController::class, 'redirectToAppClient']);
