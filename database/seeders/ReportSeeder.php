<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
class ReportSeeder extends Seeder
{
    public function run(): void
    {
        Report::factory()->count(10)->create();
    }
}
