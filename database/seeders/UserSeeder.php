<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    public function run(): void
    {

        User::factory()->count(10)->create();

        $users = [
            // Admins
            [
                'user_name' => 'Admin One',
                'email' => 'admin1@example.com',
                'phone' => '0591111111',
                'password' => Hash::make('admin123'),
                'country' => 'Palestine',
                'role_id' => 1,
                'is_approved' => true,
            ],
            [
                'user_name' => 'Admin Two',
                'email' => 'admin2@example.com',
                'phone' => '0592222222',
                'password' => Hash::make('admin123'),
                'country' => 'Jordan',
                'role_id' => 1,
                'is_approved' => true,
            ],

            // Job Owners
            [
                'user_name' => 'Job Owner One',
                'email' => 'owner1@example.com',
                'phone' => '0595555555',
                'password' => Hash::make('owner123'),
                'country' => 'Palestine',
                'role_id' => 2,
                'is_approved' => true,
            ],
            [
                'user_name' => 'Job Owner Two',
                'email' => 'owner2@example.com',
                'phone' => '0596666666',
                'password' => Hash::make('owner123'),
                'country' => 'Lebanon',
                'role_id' => 2,
                'is_approved' => true,
            ],

            // Technicians
            [
                'user_name' => 'Technician One',
                'email' => 'tech1@example.com',
                'phone' => '0593333333',
                'password' => Hash::make('tech123'),
                'country' => 'Palestine',
                'role_id' => 3,
                'is_approved' => false,
            ],
            [
                'user_name' => 'Technician Two',
                'email' => 'tech2@example.com',
                'phone' => '0594444444',
                'password' => Hash::make('tech123'),
                'country' => 'Egypt',
                'role_id' => 3,
                'is_approved' => true,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

    }
}
