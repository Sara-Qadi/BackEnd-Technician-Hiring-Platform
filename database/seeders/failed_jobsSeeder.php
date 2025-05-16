
<?php





use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FailedJobsSeeder extends Seeder
{
    public function run()
    {
        DB::table('failed_jobs')->insert([
            'uuid' => (string) Str::uuid(),
            'connection' => 'database',
            'queue' => 'default',
            'payload' => json_encode([
                'job' => 'App\\Jobs\\ExampleJob',
                'data' => ['key' => 'value'], // <-- تأكد هنا الأقواس مغلقة بشكل صحيح
            ]),
            'exception' => 'Example exception message here',
            'failed_at' => now(),
        ]);
    }
}
