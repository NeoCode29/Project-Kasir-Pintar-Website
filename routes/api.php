<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\api\StoreController;

Route::prefix("auth")->group(function () {
    Route::post("/register", [UserController::class, "register"]);
    Route::post("/login", [UserController::class, "login"]);
    Route::post("/find-email", [UserController::class, "findEmail"]);
    Route::post("/change-password", [UserController::class, "changePassword"]);
});

Route::middleware(["auth:sanctum"])->group(function () {
    Route::prefix("profile")->group(function () {
        Route::get("/{user_id}", [ProfileController::class, "getProfile"]);
        Route::post("/changeProfile", [
            ProfileController::class,
            "changeProfile",
        ]);
        Route::post("/users", [ProfileController::class, "findUserByName"]);
    });

    Route::prefix("store")->group(function () {
        Route::get("/owner/{owner_id}", [
            StoreController::class,
            "getStoresByIdOwner",
        ]);
        Route::get("/detail/{store_id}", [
            StoreController::class,
            "getStoreByIdStore",
        ]);
        Route::post("/create-store", [StoreController::class, "createStore"]);
        Route::put("/update-store", [StoreController::class, "updateStore"]);
        Route::delete("/delete-store", [StoreController::class, "deleteStore"]);
    });
});
