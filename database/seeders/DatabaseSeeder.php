<?php

namespace Database\Seeders;



use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

       $this->call([
    UserSeeder::class,            // Hamza
    JobpostSeeder::class,
    ProposalSeeder::class,
    NotificationsSeeder::class,
    ProfileSeeder::class,
     ReviewSeeder::class,       // تعليق إذا لزم الأمر
    // RolesSeeder::class,        // تعليق إذا لزم الأمر
    // LUserSeeder::class,        // LIAN
]);


      
      

    }
}
