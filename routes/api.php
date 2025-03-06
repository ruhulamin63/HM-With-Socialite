<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', function (Request $request) {
        return response()->json(['message' => 'API is working fine'], 200);
    });

    include_once 'api/v1/auth.php';
    include_once 'api/v1/hotel.php';
});