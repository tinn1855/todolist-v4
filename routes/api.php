<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;


Route::get('/users', [UserController::class, 'index']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/todos', [TodoController::class, 'index']);
    Route::post('/todos', [TodoController::class, 'store']);
    Route::patch('/todos/completed-all', [TodoController::class, 'markTodosCompletedByStatus']);
    Route::put('/todos/{id}', [TodoController::class, 'update']);
    Route::delete('/todos/delete-all', [TodoController::class, 'deleteTodosByStatus']);
    Route::delete('/todos/{id}', [TodoController::class, 'destroy']);
});



