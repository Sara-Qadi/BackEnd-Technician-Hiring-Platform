<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Notification;

class NotificationsSeeder extends Seeder
{
    public function run(): void
    {

       $user = User::create([
    'user_name' => 'Test User',
    'email' => 'testuser@example.com',
    'password' => bcrypt('password'),
    'phone' => '1234567890',
    'country' => 'TestCountry',
    'role_id' => 1,
]);


        Notification::create([
            'user_id' => $user->user_id,
            'read_status' => 'unread',
            'type' => 'test',
            'message' => 'This is a test notification for seeded user.',
        ]);
    }
}
