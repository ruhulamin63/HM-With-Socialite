<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Hotel\HotelController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('hotels', HotelController::class);
});

Route::get('all-hotels', [HotelController::class, 'allHotels']);