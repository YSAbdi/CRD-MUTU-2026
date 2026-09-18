<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'admin.dashboard')->name('home');
Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
Route::view('/admin/guests', 'admin.guests')->name('admin.guests');
Route::view('/admin/rooms', 'admin.rooms')->name('admin.rooms');
Route::view('/admin/bookings', 'admin.bookings')->name('admin.bookings');
Route::view('/admin/users', 'admin.users')->name('admin.users');
