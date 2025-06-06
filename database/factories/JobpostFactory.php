<?php

namespace Database\Factories;

use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobPostFactory extends Factory
{
    protected $model = JobPost::class;

    public function definition(): array
    {
        return [
            /*'title' => $this->faker->jobTitle,
            'category' => $this->faker->word,
            'maximum_budget' => $this->faker->numberBetween(1000, 5000),
            'minimum_budget' => $this->faker->numberBetween(500, 999),
            'deadline' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'status' => 'open',
            'attachments' => null,
            'location' => $this->faker->city,
            'description' => $this->faker->paragraph,
            'user_id' => User::inRandomOrder()->first()->user_id,*/
            'title' =>fake()->randomElement([
                'fixing my kitchen',
                'painting the walls',
                'moving furniture',
                'installing shelves',
                'repairing the roof'
            ]),
            'category' =>fake()->randomElement([
                'Carpenter',
                'Plumber',
                'Electrician',
                'Painter',
                'Mason',
                'Roofing',
                'Mechanic',
                'Welder',
                'Tiler',
                'ACTechnician',
                'CameraInstaller'
            ]),
            'maximum_budget' => 100,
            'minimum_budget' => 10,
            'deadline' => fake()->dateTimeBetween('now', '+1 year'),
            'status' => fake()->randomElement(['pending', 'in progress', 'completed']),
            //'attachments' => fake()->imageUrl(),
            'location' => fake()->city(),
            'description' => fake()->paragraph(),
            'user_id' => fake()->numberBetween(1, 7),
        ];
    }
}
