<?php

use App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\UsersController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('users', UsersController::class)->middleware('auth:sanctum');
