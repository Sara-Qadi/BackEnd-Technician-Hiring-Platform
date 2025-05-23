<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            \Database\Seeders\UserSeeder::class,
            \Database\Seeders\JobPostSeeder::class,
            \Database\Seeders\ReportSeeder::class,
            \Database\Seeders\RoleSeeder::class,
            \Database\Seeders\ReportSeeder::class,
            \Database\Seeders\ReportSeeder::class,
        ]);
    }
}
