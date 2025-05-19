<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Notification;
use Faker\Factory as Faker;
class NotificationsSeeder extends Seeder
{
    public function run(): void
    {
          $faker = Faker::create();

     for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'user_name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password'),
                'phone' => $faker->phoneNumber,
                'country' => $faker->country,
                 'role_id' => rand(1, 3),
            ]);
}


        Notification::create([
            'user_id' => $user->user_id,
            'read_status' => 'unread',
            'type' => 'test',
            'message' => 'This is a test notification for seeded user.',
        ]);
    }
}
