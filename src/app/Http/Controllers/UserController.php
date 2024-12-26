<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\ApiToken;
use App\Models\User;
use App\ImageUtil;

class UserController extends Controller
{
  const SUCCESS = true;
  const FAILURE = false;
  const SUCCESS_MESSAGE = "New user successfully registered";
  const PAGE_NOT_FOUND_MESSAGE = "Page not found";
  const VALIDATION_FAILED_MESSAGE = "Validation failed";
  const VALIDATION_COUNT_MESSAGE = "The count must be an integer.";
  const VALIDATION_PAGE_MESSAGE =  "The page must be at least 1.";
  const UNIQUE_PHONE_EMAIL_CONFLICT = "User with this phone or email already exist";

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
    // Authorize
    $token = $request->input("token");
    $isStored = ApiToken::ofToken($token);
    if ($isStored->get()->first() == null) {
      return new JsonResponse([
        "success" => self::FAILURE,
        "message" => ApiToken::TOKEN_NOT_FOUND_MESSAGE,
      ], JsonResponse::HTTP_UNAUTHORIZED);
    }

    $isNotExpired = $isStored->ofNotExpired();
    if ($isNotExpired->get()->first() == null) {
      return new JsonResponse([
        "success" => self::FAILURE,
        "message" => ApiToken::TOKEN_EXPIRED_MESSAGE,
      ], JsonResponse::HTTP_UNAUTHORIZED);
    }

    // Validate StoreUserRequest fields
    try {
      $validated = $request->validated();
    } catch (ValidationException $e) {
      $validator = $request->getValidator();
      return new JsonResponse([
        "success" => self::FAILURE,
        "message" => StoreUserRequest::VALIDATION_FAILED_MESSAGE,
        "fails" => $validator->errors(),
      ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    // Check phone email are unique for each user
    $email = $validated["email"];
    $phone = $validated["phone"];
    if (
      User::ofPhoneOrEmail($phone, $email)->get()->first() != null
    ) {
      return new JsonResponse([
        "success" => self::FAILURE,
        "message" => self::UNIQUE_PHONE_EMAIL_CONFLICT,
      ], JsonResponse::HTTP_CONFLICT);
    }

    // Photo processing
    $photo = $request->file("photo");
    if ($photo != null) {
      $photoPathToGet = ImageUtil::storeImageFileReturnPath($photo);
    }

    // Save user and report success, finally
    $newUser = User::create([
      "name" => $validated["name"],
      "email" => $email,
      "phone" => $phone,
      "photo" => $photoPathToGet,
    ]);
    $newUser->save();
    // set token is used
    $isNotExpired->is_used_already = true;
    $isNotExpired->save();

    return new JsonResponse([
      "success" => self::SUCCESS,
      "user_id" => $newUser->id,
      "message" => self::SUCCESS_MESSAGE,
    ], JsonResponse::HTTP_CREATED);
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
  public function returnUsersAsJson(Request $request)
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
    // Adjust totalPages + 1 if we have remainded number of records to show
    $total = User::count();
    $totalPages = max((int)$total / (int)$count, 1);
    if ($page > ((int)$total % (int)$count > 0 ? $totalPages + 1 : $totalPages)) {
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
