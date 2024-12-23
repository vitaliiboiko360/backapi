<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Casts\Attribute;

class ApiToken extends Model
{
  use Prunable;

  const TOKEN = 'token';
  const TOKEN_LENGTH = 128;
  const CREATED_AT = 'created_timestamp';
  const UPDATED_AT = 'updated_timestamp';
  const EXPIRED_IF_AFTER_40_MINUTES = 40;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'api_tokens';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'token_id';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [self::TOKEN];

  /**
   * The storage format of the model's date columns.
   *
   * @var string
   */
  protected $dateFormat = 'U';

  function __construct()
  {
    parent::__construct();
    $this->token = Str::random(self::TOKEN_LENGTH);
  }

  public function prunable(): Builder
  {
    return static::where(self::CREATED_AT, '<=', now()->addMinutes(self::EXPIRED_IF_AFTER_40_MINUTES)->timestamp);
  }

  /**
   * Get token.
   *
   * @return \Illuminate\Database\Eloquent\Casts\Attribute
   */
  protected function token(): Attribute
  {
    return Attribute::make(
      get: fn($value) => ($value),
    );
  }

  /**
   * Cast created_timestamp to Carbon datetime.
   *
   * @var array
   */
  protected $casts = [
    self::CREATED_AT => 'datetime',
  ];

  /**
   * Query for a given token.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @param  string  $token
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeOfToken($query, $token)
  {
    return $query->where(self::TOKEN, $token);
  }

  /**
   * Query for not expired token's lifetime.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeOfNotExpired($query)
  {
    return $query->where(self::CREATED_AT, '>=', now()->subMinutes(self::EXPIRED_IF_AFTER_40_MINUTES));
  }
}
