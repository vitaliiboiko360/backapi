<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;

class UserSeeder extends Seeder
{
  use WithoutModelEvents;
  /**
   * Run user seeder
   *
   * @return void
   */
  public function run()
  {
    User::factory()
      ->count(45)
      ->create();
  }
}
