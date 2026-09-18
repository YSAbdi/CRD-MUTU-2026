<?php

use App\Http\Controllers\Api\V1\{BookingController, CrudController, DashboardController};
use Illuminate\Support\Facades\Route;
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('dashboard', DashboardController::class);
    Route::get('guests',[CrudController::class,'guests']); Route::post('guests',[CrudController::class,'storeGuest']);
    Route::get('rooms',[CrudController::class,'rooms']); Route::post('rooms',[CrudController::class,'storeRoom']);
    Route::get('bookings',[CrudController::class,'bookings']); Route::post('bookings',[CrudController::class,'storeBooking']);
    Route::patch('bookings/{booking}/status',[BookingController::class,'updateStatus']);
});
