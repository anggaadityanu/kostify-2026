<?php

use App\Http\Controllers\Api\MidtransController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PropertyController;

/*
|------------------------------------------
| Midtrans Routes
|------------------------------------------
*/

// Endpoint untuk frontend request snap token
Route::middleware('auth')->group(function () {
    Route::post('/midtrans/snap-token', [MidtransController::class, 'getSnapToken']);
});

// Endpoint untuk Midtrans notification (tidak perlu auth)
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

//Property API (untuk frontend & maps)

Route::get('/properties', [PropertyController::class, 'index']);
Route::get('/properties/{id}', [PropertyController::class, 'show']);