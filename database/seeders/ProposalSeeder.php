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

        for ($i = 0; $i < 10; $i++) {
            Proposal::create([
                'price' => $faker->numberBetween(0, 100),
                'status_agreed' => $faker->randomElement(['pending','accepted','rejected']), // 0 for not agreed, 1 for agreed
                'description_proposal' => $faker->sentence(10),
                'tech_id' => $faker->numberBetween(1, 10),     
                'jobpost_id' => $faker->numberBetween(1, 20),  
            ]);
        }
    }
}
