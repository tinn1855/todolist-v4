<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;


Route::get('/todos', [TodoController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);
