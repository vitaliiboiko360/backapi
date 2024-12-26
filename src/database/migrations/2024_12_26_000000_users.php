<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\User;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create(User::TABLE_NAME, function (Blueprint $table) {
      $table->id();
      $table->string("name");
      $table->string("email")->unique();
      $table->string("phone")->unique();
      $table->integer("position_id")->nullable();
      $table->string("photo");
      $table->integer(User::CREATED_AT);
      $table->integer(User::UPDATED_AT);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists(User::TABLE_NAME);
  }
};
