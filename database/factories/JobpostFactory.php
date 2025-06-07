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
            'title' => $this->faker->jobTitle,
            'category' => $this->faker->word,
            'maximum_budget' => $this->faker->numberBetween(1000, 5000),
            'minimum_budget' => $this->faker->numberBetween(500, 999),
            'deadline' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'status' => 'open',
            'attachments' => null,
            'location' => $this->faker->city,
            'description' => $this->faker->paragraph,
            'user_id' => User::inRandomOrder()->first()->user_id,
        ];
    }
}
