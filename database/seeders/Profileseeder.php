<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\User;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'testuser@example.com')->first();

        if ($user) {
            Profile::create([
                'user_id' => $user->id,
                'photo' => null,
                'cv' => 'Experienced technician with skills in electronics.',
                'rating' => 5,
            ]);
        } else {
            echo "User not found. Run NotificationsSeeder first.\n";
        }
    }
}
