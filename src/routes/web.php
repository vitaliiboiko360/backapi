<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::get("/", function () {
  return view("app");
});

Route::get("/users", function () {
  return view("app");
});

Route::get("/images/users/{image_name}", [ImageController::class, "getImage"]);
