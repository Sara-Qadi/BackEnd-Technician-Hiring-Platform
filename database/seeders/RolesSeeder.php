<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['role_id' => 1, 'name' => 'Admin'],
            ['role_id' => 2, 'name' => 'Job Owner'],
            ['role_id' => 3, 'name' => 'Technician'],
        ]);
    }
}
