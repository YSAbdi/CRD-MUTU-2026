<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard');
Route::view('/dashboard', 'dashboard');
Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
