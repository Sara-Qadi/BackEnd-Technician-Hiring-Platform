<?php

namespace Database\Seeders;



use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call([
            \Database\Seeders\UserSeeder::class,
           // \Database\Seeders\JobPostSeeder::class,
         //   \Database\Seeders\ReportSeeder::class,
            \Database\Seeders\RoleSeeder::class,
          //  \Database\Seeders\ReportSeeder::class,
          //  \Database\Seeders\ReportSeeder::class,
        ]);


      
        //$this->call(RolesSeeder::class);
       //sara
     //  $this->call(UserSeeder::class,);//Hamza
      //  $this->call(JobpostSeeder::class);
       // $this->call(ProposalSeeder::class); 
      //   $this->call(NotificationsSeeder::class);
        //$this->call(LUserSeeder::class);//LIAN
       // $this->call(ProfileSeeder::class);
        //$this->call(ReviewSeeder::class);

    }
}
