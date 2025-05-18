<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proposal;
use Faker\Factory as Faker;

class ProposalSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 50; $i++) {
            Proposal::create([
                'price' => $faker->randomFloat(2, 50, 1000),  // سعر عشوائي بين 50 و 1000 مع خانتين عشريتين
                'description_proposal' => $faker->sentence(10), // جملة عشوائية 10 كلمات
                'submission_id' => $faker->numberBetween(1, 20), // عدل العدد حسب عدد الـ submissions عندك
            ]);
        }
    }
}
