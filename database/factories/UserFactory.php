<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $roleId = Role::inRandomOrder()->first()->role_id ?? 1;

        return [
            'user_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => substr($this->faker->phoneNumber(), 0, 10),
            'password' => Hash::make('password'),
            'country' => $this->faker->country(),
            'role_id' => $roleId,
        ];
    }
}
