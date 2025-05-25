<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        //$this->call(NotificationsSeeder::class);
        //$this->call(RolesSeeder::class);
        $this->call(ProfileSeeder::class);//sara
      
        $this->call(JobpostSeeder::class);
        $this->call(ProposalSeeder::class);
        //$this->call(LUserSeeder::class);//LIAN
        $this->call(UserSeeder::class,);//Hamza
    }
}
