<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ReviewSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
{
    $this->call([
        Ueserseeder::class,
        Profileseeder::class,
        Jobpostseeder::class,
        ReviewSeeder::class,
    ]);
}
}
