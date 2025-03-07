<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Auth\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::post('auth/login', 'login');
    Route::post('auth/refresh-token', 'refreshToken');
    
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('auth/logout', 'logout');
    });
});