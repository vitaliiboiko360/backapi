<?php

namespace Database\Factories;

use App\Constants;
use App\ImageUtil;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      "name" => $this->faker->name(),
      "email" => $this->faker->unique()->safeEmail(),
      "phone" => "+380" . array_rand(Constants::UKRAINE_MOBILE_CODES) . strval($this->faker->unique()->numberBetween(1000000, 9999999)),
      "photo" => ImageUtil::DEFAULT_PHOTO,
      "registration_timestamp" => now()->getTimestamp(),
      "updated_timestamp" => now()->getTimestamp(),
    ];
  }

  /**
   * Disabled
   *
   * @return static
   */
  public function unverified()
  {
    return false; /*
    return $this->state(function () {
      return [
        "email_verified_at" => null,
      ];
    }); */
  }
}
