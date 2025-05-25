<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\Jobpost;
class JobpostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' =>fake()->randomElement([
                'fixing my kitchen',
                'painting the walls',
                'moving furniture',
                'installing shelves',
                'repairing the roof'
            ]),
            'category' =>fake()->randomElement([
                'carpenter',
                'plumber',
                'electrician',
                'painter'
            ]),
            'maximum_budget' => 100,
            'minimum_budget' => 10,
            'deadline' => fake()->dateTimeBetween('now', '+1 year'),
            'status' => fake()->randomElement(['pending', 'in progress', 'completed']),
            'attachments' => fake()->imageUrl(),
            'location' => fake()->city(),
            'description' => fake()->paragraph(),
            'user_id' => fake()->numberBetween(1, 7),
        ];
    }
}
