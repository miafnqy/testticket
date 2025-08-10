<?php

use App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\UserController;
use \App\Http\Controllers\Api\RoleController;
use Illuminate\Support\Facades\Route;

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
Route::apiResource('roles', RoleController::class)->middleware('auth:sanctum');
