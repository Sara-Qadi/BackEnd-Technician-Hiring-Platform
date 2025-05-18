<?php

namespace Database\Seeders;
use Faker\Generator;
use Faker\Factory as Faker;
use \App\Models\Jobpost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobpostSeeder extends Seeder
{
    public function run(): void
    {
         $faker = Faker::create();
        for($i=0;$i<100;$i++){
            Jobpost::create([
                'title'=>$faker->randomElement(['fixing my kitchen','painting the walls', 'moving furniture', 'installing shelves', 'repairing the roof']),
                'category'=>"carpenter",
                'maximum_budget'=>"100",
                'minimum_budget'=>"10",
                'deadline'=>$faker->dateTimeBetween('now', '+1 year'),
                'status'=>$faker->randomElement(['pending','in progress', 'completed']),
                'attachments'=>$faker->imageUrl(),
                'location'=>$faker->city(),
                'description'=>$faker->paragraph(),
                'user_id'=>$faker->numberBetween(1, 10), // Assuming you have 10 users
            ]);
        }
    }
}
