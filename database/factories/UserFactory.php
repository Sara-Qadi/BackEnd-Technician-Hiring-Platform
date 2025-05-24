<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'user_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // كلمة مرور وهمية
            'phone' => $this->faker->phoneNumber(),
            'country' => $this->faker->country(),
            'role_id' => 1, // تأكد أن role_id = 1 موجود في جدول roles
        ];
    }
}
