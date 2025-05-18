<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Submission;
use Faker\Factory as Faker;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 50; $i++) {
            Submission::create([
                'tech_id' => $faker->numberBetween(1, 5),       // غير الأرقام حسب عدد المستخدمين عندك
                'jobpost_id' => $faker->numberBetween(1, 20),  // غير حسب عدد job posts عندك
                'status_agreed' => $faker->boolean(50),        // 50% احتمال true أو false
            ]);
        }
    }
}


