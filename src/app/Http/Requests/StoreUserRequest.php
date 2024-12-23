<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use  Illuminate\Contracts\Validation\Validator;

class StoreUserRequest extends FormRequest
{
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
      'name.required' => 'The name must be at least 2 and at most 60 characters long.',
      'email.required' => 'The email must be a valid email address.',
      'phone.required' => 'The phone field is required. Number should start with code of Ukraine +380.',
      'position_id' => 'The position id must be an integer.',
      'photo' => 'The photo may not be greater than 5 Mbytes.',
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
