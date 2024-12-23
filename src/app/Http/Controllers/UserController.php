<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Validation\ValidationException;

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
   * Store a new user.
   *
   * @param  \App\Http\Requests\StoreUserRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreUserRequest $request)
  {
    // Validate and store the user...
    try {
      $validated = $request->validated();
      $newUser = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
      ]);
      $newUser->save();
    } catch (ValidationException $e) {

      $validator = $request->getValidator();
      return new JsonResponse([
        "sucess" => false,
        "message" => "Validation failed",
        "fails" => $validator->errors(),
      ]);
    }
  }
}
