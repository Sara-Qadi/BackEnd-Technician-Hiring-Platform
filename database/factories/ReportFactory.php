<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use App\Models\JobPost;
use Illuminate\Database\Eloquent\Factories\Factory;


class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $jobPost = JobPost::inRandomOrder()->first() ?? JobPost::factory()->create();

        return [
            'user_id' => $user->user_id,
            'jobpost_id' => $jobPost->jobpost_id,
            'reason' => $this->faker->sentence,
            'report_type' => $this->faker->randomElement(['spam', 'abuse', 'fraud']),
        ];
    }

}

