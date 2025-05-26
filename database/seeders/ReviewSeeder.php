<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Jobpost;
use App\Models\Profile;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $jobposts = Jobpost::all();
        $usedProfiles = []; 

        if ($users->isNotEmpty() && $jobposts->isNotEmpty()) {
            foreach ($jobposts as $job) {
                $reviewer = $users->random();
                $reviewee = $users->where('user_id', '!=', $reviewer->user_id)->random();

                if (
                    Profile::where('user_id', $reviewee->user_id)->exists() &&
                    !in_array($reviewee->user_id, $usedProfiles)
                ) {
                    Review::create([
                        'review_by' => $reviewer->user_id,
                        'review_to' => $reviewee->user_id,
                        'profile_id' => $reviewee->user_id,
                        'jobpost_id' => $job->jobpost_id,
                        'rating' => rand(1, 5),
                        'review_comment' => 'Excellent work by user ' . $reviewee->user_id,
                    ]);

                    $usedProfiles[] = $reviewee->user_id;
                }
            }
        }
    }
}
