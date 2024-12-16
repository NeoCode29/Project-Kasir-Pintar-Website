<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/find-email', [UserController::class, 'findEmail']);
Route::post('/change-password', [UserController::class, 'changePassword']);


