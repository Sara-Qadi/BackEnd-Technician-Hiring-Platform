<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Insert 10 fake users
        for ($i = 0; $i < 10; $i++) {
            DB::table('users')->insert([
                'UserName'  => $faker->name,
                'Email'     => $faker->unique()->safeEmail,
                'Phone'     => $faker->phoneNumber,
                'Password'  => Hash::make('password'), // Hash the password
                'Country'   => $faker->country,
            ]);
        }
    }
}