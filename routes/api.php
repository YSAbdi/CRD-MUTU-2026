<?php

use App\Http\Controllers\Api\V1\{BookingController, DashboardController};
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('dashboard', DashboardController::class);
    Route::apiResource('guests', \App\Http\Controllers\Api\V1\GuestController::class)->only(['index', 'store', 'show', 'update']);
    Route::apiResource('rooms', \App\Http\Controllers\Api\V1\RoomController::class)->only(['index', 'store', 'show', 'update']);
    Route::get('bookings', [BookingController::class, 'index']);
    Route::post('bookings', [BookingController::class, 'store']);
    Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus']);
});
