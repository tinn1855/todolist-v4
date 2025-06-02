<?php

use App\Http\Controllers\TodoController;

Route::get('/todos', [TodoController::class, 'index']);
