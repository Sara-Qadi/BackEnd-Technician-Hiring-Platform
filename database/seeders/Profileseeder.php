<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء ملف شخصي لكل مستخدم
        User::all()->each(function ($user) {
            Profile::create([
                'user_id' => $user->user_id,
                'photo' => null,
                'cv' => 'Dummy CV content for user '.$user->user_id,
                'rating' => rand(1, 5),
            ]);
        });
    }
}
