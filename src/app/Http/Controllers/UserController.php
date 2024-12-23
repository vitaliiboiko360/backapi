<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Validation\ValidationException;

use App\Models\ApiToken;

use Illuminate\Http\JsonResponse;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;

class UserController extends Controller
{
  /**
   * Show the form to create a new user.
   *
   * @return \Illuminate\View\View
   */
  public function create()
  {
    return view('user.create');
  }

  /**
   * Process StoreUserRequest.
   *
   * @param  \App\Http\Requests\StoreUserRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreUserRequest $request)
  {
    // authorize
    $token = $request->input('token');
    $isStored = ApiToken::where('token', $token);

    // Validate store user request
    try {
      $validated = $request->validated();
    } catch (ValidationException $e) {
      $validator = $request->getValidator();
      return new JsonResponse([
        "sucess" => false,
        "message" => "Validation failed",
        "fails" => $validator->errors(),
      ]);
    }

    // check phone email unique for each user


    $newUser = User::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'phone' => $validated['phone'],
    ]);
    $newUser->save();
  }
}
