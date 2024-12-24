<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use  Illuminate\Contracts\Validation\Validator;

class StoreUserRequest extends FormRequest
{
  const VALIDATION_FAILED_MESSAGE = 'Validation failed';
  const VALIDATION_ERROR_NAME = 'The name must be at least 2 and at most 60 characters long.';
  const VALIDATION_ERROR_EMAIL = 'The email must be a valid email address.';
  const VALIDATION_ERROR_PHONE_REQUIRED = 'The phone field is required';
  const VALIDATION_ERROR_PHONE_FORMAT = 'Phone number should start with code of Ukraine +380 and contain 9 digits after code.';
  const VALIDATION_ERROR_POSITION_ID = 'The position id must be an integer.';
  const VALIDATION_ERROR_PHOTO_SIZE = 'The photo may not be greater than 5 Mbytes.';

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
   * Determine if the user is authorized to make this request.
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
      'name' => 'required|min:2|max:60',
      'email' => 'required|email:rfc',
      'phone' => 'required|regex:/^\+380[0-9]{9}$/',
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
      'name.required' => self::VALIDATION_ERROR_NAME,
      'name.min' => self::VALIDATION_ERROR_NAME,
      'name.max' => self::VALIDATION_ERROR_NAME,
      'email.required' => self::VALIDATION_ERROR_EMAIL,
      'email.email' => self::VALIDATION_ERROR_EMAIL,
      'phone.required' => self::VALIDATION_ERROR_PHONE_FORMAT,
      'phone.regex' => self::VALIDATION_ERROR_PHONE_FORMAT,
      'position_id' => self::VALIDATION_ERROR_POSITION_ID,
      'photo' => self::VALIDATION_ERROR_PHOTO_SIZE,
    ];
  }

  /**
   * We have our own custom response to failed validation.
   *
   * @param  \Illuminate\Contracts\Validation\Validator  $validator
   * @return void
   *
   * @throws \Illuminate\Validation\ValidationException
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
