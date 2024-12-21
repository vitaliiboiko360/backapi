<?php

use App\Models\ApiToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

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

Route::get("/token", function () {
  $newToken = Str::random(128);
  ApiToken::create([
    'token' => $newToken,
  ]);
  return new JsonResponse([
    "sucess" => true,
    "token" => $newToken,
  ]);
});

Route::get("/test/error", function () {
  throw new BadRequestHttpException("Test Error Trace");
});
