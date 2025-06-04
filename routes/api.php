<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;


Route::get('/users', [UserController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/todos', [TodoController::class, 'index']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::group(['prefix' => 'api', 'as' => 'api.', 'middleware' => 'validate_api_token'], function () {
    Route::post('api/checkLogin', 'ApiController@getLoginStatus')->name('check_login_status');
});


