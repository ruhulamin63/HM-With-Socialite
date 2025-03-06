<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Auth\AuthController;
use App\Http\Controllers\API\V1\Hotel\HotelController;

Route::controller(AuthController::class)->group(function () {
    Route::post('auth/register', 'register');
    Route::post('auth/login', 'login');
    Route::post('google/refresh-token', 'refreshToken');
    
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', 'logout');
        Route::apiResource('hotels', HotelController::class);
    });
});