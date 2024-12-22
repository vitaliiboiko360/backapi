<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  const CREATED_AT = 'registration_timestamp';
  const UPDATED_AT = 'updated_timestamp';

  protected $dateFormat = 'U';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = ["name", "email", "phone", "position_id", "photo"];
}
