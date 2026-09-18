<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('throttle:api')->group(function () {
    Route::post('auth/register', [\App\Http\Controllers\Api\V1\AuthController::class, 'register']);
    Route::post('auth/login', [\App\Http\Controllers\Api\V1\AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/me', [\App\Http\Controllers\Api\V1\AuthController::class, 'me']);
        Route::post('auth/logout', [\App\Http\Controllers\Api\V1\AuthController::class, 'logout']);
        Route::post('push-subscriptions', [\App\Http\Controllers\Api\V1\PushSubscriptionController::class, 'store']);
        Route::delete('push-subscriptions', [\App\Http\Controllers\Api\V1\PushSubscriptionController::class, 'destroy']);
        Route::get('dashboard', \App\Http\Controllers\Api\V1\DashboardController::class);
        Route::get('calendar', [\App\Http\Controllers\Api\V1\CalendarController::class, 'index']);
        Route::get('guests', [\App\Http\Controllers\Api\V1\CrudController::class, 'guests']); Route::post('guests', [\App\Http\Controllers\Api\V1\CrudController::class, 'storeGuest']); Route::put('guests/{guest}', [\App\Http\Controllers\Api\V1\CrudController::class, 'updateGuest']); Route::delete('guests/{guest}', [\App\Http\Controllers\Api\V1\CrudController::class, 'deleteGuest']);
        Route::get('rooms', [\App\Http\Controllers\Api\V1\CrudController::class, 'rooms']); Route::post('rooms', [\App\Http\Controllers\Api\V1\CrudController::class, 'storeRoom']); Route::put('rooms/{room}', [\App\Http\Controllers\Api\V1\CrudController::class, 'updateRoom']);
        Route::get('bookings', [\App\Http\Controllers\Api\V1\CrudController::class, 'bookings']); Route::post('bookings', [\App\Http\Controllers\Api\V1\CrudController::class, 'storeBooking']); Route::put('bookings/{booking}', [\App\Http\Controllers\Api\V1\CrudController::class, 'updateBooking']); Route::delete('bookings/{booking}', [\App\Http\Controllers\Api\V1\CrudController::class, 'deleteBooking']); Route::patch('bookings/{booking}/status', [\App\Http\Controllers\Api\V1\BookingController::class, 'updateStatus']);
        Route::get('reports/bookings', [\App\Http\Controllers\Api\V1\ReportController::class, 'bookings']);
        Route::middleware('role:superadmin')->group(function () { Route::get('users', [\App\Http\Controllers\Api\V1\UserController::class, 'index']); Route::post('users', [\App\Http\Controllers\Api\V1\UserController::class, 'store']); Route::put('users/{user}', [\App\Http\Controllers\Api\V1\UserController::class, 'update']); Route::delete('users/{user}', [\App\Http\Controllers\Api\V1\UserController::class, 'destroy']); });
    });
});
