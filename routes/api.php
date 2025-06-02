<?php

use App\Http\Controllers\Api\TodoController;

Route::get('/todos', [TodoController::class, 'index']);
