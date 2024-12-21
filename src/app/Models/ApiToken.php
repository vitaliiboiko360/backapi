<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
  use Prunable;

  const CREATED_AT = 'created_timestamp';
  const UPDATED_AT = 'updated_timestamp';
  const EXPIRE_AFTER_40_MINUTES = 40;

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
  protected $fillable = ["token"];

  /**
   * The storage format of the model's date columns.
   *
   * @var string
   */
  protected $dateFormat = 'U';

  /**
   * Get the prunable model query.
   */
  public function prunable(): Builder
  {
    return static::where(self::CREATED_AT, '<=', now()->addMinutes(self::EXPIRE_AFTER_40_MINUTES));
  }
}
