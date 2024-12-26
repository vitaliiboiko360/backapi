<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;

Route::get("/token", [ApiTokenController::class, "createTokenAndReturnAsJson"]);

Route::post("/users", [UserController::class, "processStoreUserRequest"]);

Route::get("/users", [UserController::class, "returnUsersAsJson"]);

Route::get("/positions", [PositionController::class, "returnPositionsAsJson"]);
