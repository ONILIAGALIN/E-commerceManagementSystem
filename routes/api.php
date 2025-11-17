<?php

use Illuminate\Support\Facades\Route;

Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

Route::post('/users', [App\Http\Controllers\UserController::class, 'store']);
Route::get('/users', [App\Http\Controllers\UserController::class, 'index']);
Route::post('/rooms', [App\Http\Controllers\RoomController::class, 'store']);
Route::get('/rooms', [App\Http\Controllers\RoomController::class, 'index']);
