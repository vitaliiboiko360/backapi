<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\ApiToken;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create(ApiToken::TABLE_NAME, function (Blueprint $table) {
      $table->increments(ApiToken::TOKEN_ID);
      $table->string(ApiToken::TOKEN);
      $table->boolean(ApiToken::IS_USED_ALREADY);
      $table->integer(ApiToken::CREATED_AT);
      $table->integer(ApiToken::UPDATED_AT);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists(ApiToken::TABLE_NAME);
  }
};
