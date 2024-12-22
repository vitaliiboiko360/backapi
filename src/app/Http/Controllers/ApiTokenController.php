<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use Illuminate\Http\JsonResponse;

class ApiTokenController extends Controller
{
  /**
   * Store a new api token in the database.
   *
   * @return \Illuminate\Http\Response
   */
  public function store()
  {
    $apiToken = new ApiToken();
    $apiToken->save();
  }

  public function createTokenAndReturnAsJson(): JsonResponse
  {
    $apiToken = new ApiToken();
    $apiToken->save();
    return new JsonResponse([
      "sucess" => true,
      "token" => $apiToken->token,
    ]);
  }
}
