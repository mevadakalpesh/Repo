<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Seeder
{
  /**
  * Run the database seeds.
  */
  public function run(): void
  {
    User::updateOrCreate([
      "email" => "admin@test.com",
      "password" => Hash::make('admin@test.com')
    ], [
      "name" => "Admin",
      "email" => "admin@test.com",
      "password" => Hash::make('admin@test.com')
    ]);
  }
}