<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  const UKRAINE_MOBILE_CODES = ["66", "99", "95", "50", "68", "96", "86", "63", "93"];
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
      "phone" => "+380" . array_rand(self::UKRAINE_MOBILE_CODES) . strval($this->faker->unique()->numberBetween(1000000, 9999999)),
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
