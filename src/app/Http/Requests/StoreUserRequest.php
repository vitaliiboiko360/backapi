<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

use App\Constants;

class StoreUserRequest extends FormRequest
{
  const VALIDATION_FAILED_MESSAGE = "Validation failed";
  const VALIDATION_ERROR_NAME = "The name must be at least 2 and at most 60 characters long.";
  const VALIDATION_ERROR_EMAIL = "The email must be a valid email address.";
  const VALIDATION_ERROR_PHONE_REQUIRED = "The phone field is required";
  const VALIDATION_ERROR_PHONE_FORMAT = "Phone number should start with code of Ukraine +380 and contain 9 digits after code.";
  const VALIDATION_ERROR_POSITION_ID = "The position id must be an integer.";
  const VALIDATION_ERROR_PHOTO_MAXSIZE = "The photo may not be greater than 5 Mbytes.";
  const VALIDATION_ERROR_PHOTO_DIMS = "Minimum size of the photo must be 70x70px.";
  const VALIDATION_ERROR_PHOTO_FORMAT = "The photo format must be jpeg/jpg type.";
  const VALIDATION_ERROR_POSITION_RANGE = "Positions not found";

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Indicates if the validator should stop on the first rule failure.
   *
   * @var bool
   */
  protected $stopOnFirstFailure = false;

  /**
   * We have our own custom authorization in UserController.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      "name" => "required|min:2|max:60",
      "email" => "required|email:rfc",
      "phone" => "required|regex:/^\+380[0-9]{9}$/",
      "position_id" => "required|numeric|min:" . strval(Constants::POSITION_ID_MIN) . "|max:" . strval(Constants::POSITION_ID_MAX),
      "photo" => [
        File::image()
          ->types(["jpeg", "jpg"])
          ->max(Constants::PHOTO_MAX_FILESIZE)
          ->dimensions(Rule::dimensions()
            ->minWidth(Constants::PHOTO_MIN_DIM_WIDTH)
            ->minHeight(Constants::PHOTO_MIN_DIM_HEIGHT)),
      ]
    ];
  }

  /**
   * Get the error messages for the defined validation rules.
   *
   * @return array
   */
  public function messages()
  {
    return [
      "name.required" => self::VALIDATION_ERROR_NAME,
      "name.min" => self::VALIDATION_ERROR_NAME,
      "name.max" => self::VALIDATION_ERROR_NAME,
      "email.required" => self::VALIDATION_ERROR_EMAIL,
      "email.email" => self::VALIDATION_ERROR_EMAIL,
      "phone.required" => self::VALIDATION_ERROR_PHONE_FORMAT,
      "phone.regex" => self::VALIDATION_ERROR_PHONE_FORMAT,
      "photo.max" => self::VALIDATION_ERROR_PHOTO_MAXSIZE,
      "photo.dimensions" => self::VALIDATION_ERROR_PHOTO_DIMS,
      "photo.types" => self::VALIDATION_ERROR_PHOTO_FORMAT,
      "position_id.required" => self::VALIDATION_ERROR_POSITION_ID,
      "position_id.numeric" => self::VALIDATION_ERROR_POSITION_ID,
      "position_id.min" => self::VALIDATION_ERROR_POSITION_RANGE,
      "position_id.max" => self::VALIDATION_ERROR_POSITION_RANGE,
    ];
  }

  /**
   * We have our own custom response to failed validation.
   *
   * @param  \Illuminate\Contracts\Validation\Validator  $validator
   * @return void
   */
  protected function failedValidation(Validator $validator) {}

  /**
   * @return \Illuminate\Contracts\Validation\Validator
   */
  public function getValidator()
  {
    return $this->validator;
  }
}
