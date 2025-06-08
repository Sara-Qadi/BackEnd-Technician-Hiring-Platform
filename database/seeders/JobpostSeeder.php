<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPost;

class JobPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jobpost::factory()->count(10)->create();
    }
}
