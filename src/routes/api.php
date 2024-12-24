<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("/user", function (Request $request) {
  return $request->user();
})->middleware('auth.basic.once');

Route::get("/test", function () {
  return new JsonResponse("Hello, API");
});

Route::get("/token", [ApiTokenController::class, 'createTokenAndReturnAsJson']);

Route::post("/users", [UserController::class, 'processStoreUserRequest']);

Route::get("/test/error", function () {
  throw new BadRequestHttpException("Test Error Trace");
});
