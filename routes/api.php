<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;


// Route::get('/todos', [TodoController::class, 'index']);
Route::middleware('auth:sanctum')->get('/todos', [TodoController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

