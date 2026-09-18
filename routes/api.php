<?php

use App\Http\Controllers\Api\V1\{AuthController, BookingController, CrudController, DashboardController, UserController};
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('auth/register',[AuthController::class,'register']);
    Route::post('auth/login',[AuthController::class,'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/me',[AuthController::class,'me']); Route::post('auth/logout',[AuthController::class,'logout']);
        Route::get('dashboard', DashboardController::class);
        Route::get('guests',[CrudController::class,'guests']); Route::post('guests',[CrudController::class,'storeGuest']);
        Route::get('rooms',[CrudController::class,'rooms']); Route::post('rooms',[CrudController::class,'storeRoom']);
        Route::get('bookings',[CrudController::class,'bookings']); Route::post('bookings',[CrudController::class,'storeBooking']);
        Route::patch('bookings/{booking}/status',[BookingController::class,'updateStatus']);
        Route::middleware('role:superadmin')->group(function () { Route::get('users',[UserController::class,'index']); Route::put('users/{user}',[UserController::class,'update']); });
    });
});
