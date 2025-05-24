<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class JobpostFactory extends Factory
{
    public function definition(): array
    {
        $minBudget = $this->faker->numberBetween(0, 1000);
        $maxBudget = $this->faker->numberBetween($minBudget, 2000);

        return [
            'title' => $this->faker->jobTitle(),
            'category' => $this->faker->word(), 
            'maximum_budget' => $maxBudget,
            'minimum_budget' => $minBudget,
            'deadline' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'status' => 'pending',  
            'attachments' => null, 
            'location' => $this->faker->city(),
            'description' => $this->faker->optional()->paragraph(),
            
        ];
    }
}
