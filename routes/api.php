<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/find-email', [UserController::class, 'findEmail']);
Route::post('/change-password', [UserController::class, 'changePassword']);
Route::post('/change-profile',[ProfileController::class,"changeProfile"]);

