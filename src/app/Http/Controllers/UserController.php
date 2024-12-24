<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Validation\ValidationException;

use App\Models\ApiToken;

use Illuminate\Http\JsonResponse;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
  const SUCCESS = true;
  const FAILURE = false;
  const SUCCESS_MESSAGE = "New user successfully registered";
  const PAGE_NOT_FOUND_MESSAGE = "Page not found";
  const VALIDATION_FAILED_MESSAGE = "Validation failed";

  const PAGE_COUNT = 5;

  /**
   * Show the form to create a new user.
   *
   * @return \Illuminate\View\View
   */
  public function create()
  {
    return view("user.create");
  }

  /**
   * Process StoreUserRequest.
   *
   * @param  \App\Http\Requests\StoreUserRequest  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function processStoreUserRequest(StoreUserRequest $request)
  {
    // authorize
    $token = $request->input("token");
    $isStored = ApiToken::ofToken($token);
    if ($isStored->get() == null) {
      return new JsonResponse([
        "sucess" => self::FAILURE,
        "message" => ApiToken::TOKEN_NOT_FOUND_MESSAGE,
      ], JsonResponse::HTTP_UNAUTHORIZED);
    }

    $isNotExpired = $isStored->ofNotExpired();
    if ($isNotExpired->get() == null) {
      return new JsonResponse([
        "sucess" => self::FAILURE,
        "message" => ApiToken::TOKEN_EXPIRED_MESSAGE,
      ], JsonResponse::HTTP_UNAUTHORIZED);
    }

    // Validate store user request fields
    try {
      $validated = $request->validated();
    } catch (ValidationException $e) {
      $validator = $request->getValidator();
      return new JsonResponse([
        "sucess" => self::FAILURE,
        "message" => StoreUserRequest::VALIDATION_FAILED_MESSAGE,
        "fails" => $validator->errors(),
      ]);
    }

    // check phone email unique for each user


    $newUser = User::create([
      "name" => $validated["name"],
      "email" => $validated["email"],
      "phone" => $validated["phone"],
    ]);
    $newUser->save();
    return new JsonResponse([
      "sucess" => self::SUCCESS,
      "user_id" => $newUser->id,
      "message" => self::SUCCESS_MESSAGE,
    ]);
  }


  /**
   * @param \App\Http\Requests\Request $request
   * @return array
   */
  private function validateUrlQueryParamsForUsersPagination(Request $request)
  {
    // make falidation

  }

  /**
   * @param \App\Http\Requests\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function users(Request $request)
  {
    $count = $request->query("count");

    if (is_numeric($count)) {
      return new JsonResponse([
        "success" => self::FAILURE,
        "message"
      ]);
    }

    $paginator = User::paginate(
      $perPage = $count > 0 ? $count : self::PAGE_COUNT,
      $columns = [
        "id",
        "name",
        "email",
        "phone",
        "position_id",
        "registration_timestamp",
        "photo",
      ],
      $pageName = "page"
    );

    return $paginator;

    // $page = $request->query("page");

    // return new JsonResponse([
    //   "success" => self::FAILURE,
    //   "message" => self::PAGE_NOT_FOUND_MESSAGE,
    // ]);
  }
}
