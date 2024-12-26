<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  const CREATED_AT = "registration_timestamp";
  const UPDATED_AT = "updated_timestamp";
  const TABLE_NAME = "users";

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = self::TABLE_NAME;

  protected $dateFormat = "U";

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = ["name", "email", "phone", "position_id", "photo"];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    self::CREATED_AT => 'datetime:U',
  ];

  /**
   * Query for a given phone or email.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @param  string  $phone
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeOfPhoneOrEmail($query, $phone, $email)
  {
    return $query
      ->where("phone", $phone)
      ->orWhere("email", $email);
  }

  /**
   * Query for a given email
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @param  string  $email
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeOfEmail($query, $email)
  {
    return $query->where("email", $email);
  }
}
