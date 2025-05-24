<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\JobPost;





class Jobpostseeder extends Seeder
{
   public function run(): void
    {
        
        User::all()->each(function ($user) {
            Jobpost::factory()->count(2)->create([
                'user_id' => $user->user_id,
            ]);
        });
    }
}
