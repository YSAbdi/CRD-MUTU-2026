<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard');
Route::view('/dashboard', 'dashboard')->middleware('auth');
