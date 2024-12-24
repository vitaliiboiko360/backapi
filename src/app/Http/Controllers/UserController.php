<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Validation\ValidationException;

use App\Models\ApiToken;

use Illuminate\Http\JsonResponse;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  const SUCCESS = true;
  const FAILURE = false;
  const SUCCESS_MESSAGE = "New user successfully registered";
  const PAGE_NOT_FOUND_MESSAGE = "Page not found";
  const VALIDATION_FAILED_MESSAGE = "Validation failed";
  const VALIDATION_COUNT_MESSAGE = "The count must be an integer.";
  const VALIDATION_PAGE_MESSAGE =  "The page must be at least 1.";

  // Default pagination parameters
  const PAGE_COUNT = 5;
  const PAGE_NUMBER = 1;

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
   * @param array $urlQueryParams
   * @return array
   */
  private function validateUrlQueryParamsForUsersPagination(array $urlQueryParams)
  {
    // Make validation
    $validator = Validator::make(
      $urlQueryParams,
      $rules = [
        "count" => "numeric",
        "page" => "numeric|min:1",
      ],
      $messages = [
        "count" => self::VALIDATION_COUNT_MESSAGE,
        "page" => self::VALIDATION_PAGE_MESSAGE,
      ]
    );

    return [
      "status" => $validator->fails() ? self::FAILURE : self::SUCCESS,
      "resultsJsonResponse" => $validator->fails() ? new JsonResponse([
        "success" => self::FAILURE,
        "message" => self::VALIDATION_FAILED_MESSAGE,
        "fails" => $validator->errors(),
      ]) : [],
    ];
  }

  /**
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function users(Request $request)
  {
    $count = $request->query("count", self::PAGE_COUNT);
    $page = $request->query("page", self::PAGE_NUMBER);
    $urlQueryParams = [
      "count" => $count,
      "page" => $page,
    ];
    $resultValidation = $this->validateUrlQueryParamsForUsersPagination($urlQueryParams);
    if ($resultValidation["status"] == self::FAILURE) {
      return $resultValidation["resultsJsonResponse"];
    }

    // Check if $page not overflow total pages number
    $total = User::count();
    $totalPages = max($total / $count, 1);
    if ($page > $totalPages) {
      return new JsonResponse([
        "success" => self::FAILURE,
        "message" => self::PAGE_NOT_FOUND_MESSAGE,
      ]);
    }

    $paginator = User::paginate(
      $perPage = $count,
      $columns = [
        "id",
        "name",
        "email",
        "phone",
        "position_id",
        "registration_timestamp",
        "photo",
      ],
      $pageName = "page",
      $page = $page,
    );

    return new JsonResponse([
      "success" => self::SUCCESS,
      "page" => $paginator->currentPage(),
      "total_pages" => $paginator->lastPage(),
      "total_users" => $paginator->total(),
      "count" => $paginator->count(),
      "links" => [
        "next_url" => $paginator->nextPageUrl(),
        "prev_url" => $paginator->previousPageUrl(),
      ],
      "users" => $paginator->items(),
    ]);
  }
}
