<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPost;
use App\Models\User;

class JobPostSeeder extends Seeder
{
    public function run(): void
    {
        // تأكد من وجود مستخدمين
        if (User::count() == 0) {
            \App\Models\User::factory(5)->create();
        }

        // أنشئ 10 وظائف وهمية
        JobPost::factory(10)->create();
    }
}
