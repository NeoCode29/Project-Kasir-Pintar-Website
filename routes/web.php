<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/latihan', function () {
    return view('latihan');
});

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);