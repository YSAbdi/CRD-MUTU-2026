<?php

use App\Http\Controllers\Api\V1\{AuthController, BookingController, CalendarController, CrudController, DashboardController, ReportController, UserController};
use Illuminate\Support\Facades\Route;
Route::prefix('v1')->middleware('throttle:api')->group(function () {
    Route::post('auth/register',[AuthController::class,'register']); Route::post('auth/login',[AuthController::class,'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/me',[AuthController::class,'me']); Route::post('auth/logout',[AuthController::class,'logout']); Route::get('dashboard',DashboardController::class); Route::get('calendar',[CalendarController::class,'index']);
        Route::get('guests',[CrudController::class,'guests']); Route::post('guests',[CrudController::class,'storeGuest']); Route::put('guests/{guest}',[CrudController::class,'updateGuest']); Route::delete('guests/{guest}',[CrudController::class,'deleteGuest']);
        Route::get('rooms',[CrudController::class,'rooms']); Route::post('rooms',[CrudController::class,'storeRoom']); Route::put('rooms/{room}',[CrudController::class,'updateRoom']);
        Route::get('bookings',[CrudController::class,'bookings']); Route::post('bookings',[CrudController::class,'storeBooking']); Route::put('bookings/{booking}',[CrudController::class,'updateBooking']); Route::delete('bookings/{booking}',[CrudController::class,'deleteBooking']); Route::patch('bookings/{booking}/status',[BookingController::class,'updateStatus']);
        Route::get('reports/bookings',[ReportController::class,'bookings']);
        Route::middleware('role:superadmin')->group(function () { Route::get('users',[UserController::class,'index']); Route::put('users/{user}',[UserController::class,'update']); });
    });
});
