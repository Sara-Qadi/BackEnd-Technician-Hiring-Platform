<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
  public function jobCompletionReport()
    {
        $data = DB::table('jobposts')
            ->selectRaw('MONTH(created_at) as month_number')
            ->selectRaw('MONTHNAME(MIN(created_at)) as month')
            ->selectRaw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_jobs')
            ->selectRaw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_jobs')
            ->selectRaw('SUM(CASE WHEN status = "in_progress" THEN 1 ELSE 0 END) as in_progress_jobs')
            ->selectRaw('ROUND(SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) * 100 / COUNT(*), 1) as completion_rate')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month_number')
            ->get();

        return response()->json([
            'headers' => ['Month', 'Completed Jobs', 'Cancelled Jobs', 'In Progress Jobs', 'Completion Rate'],
            'data' => $data->map(function ($row) {
                return [
                    $row->month,
                    $row->completed_jobs,
                    $row->cancelled_jobs,
                    $row->in_progress_jobs,
                    $row->completion_rate . '%',
                ];
            }),
        ]);
    }
     public function earningsReport()
    {
        $report = DB::table('users')
            ->join('jobposts', 'users.user_id', '=', 'jobposts.user_id')
            ->join('reviews', 'users.user_id', '=', 'reviews.review_to')
            ->select(
                'users.user_id',
                DB::raw('MAX(users.user_name) as user_name'),
                DB::raw('COUNT(jobposts.jobpost_id) as completed_jobs'),
                DB::raw('ROUND(AVG(jobposts.maximum_budget), 2) as avg_job_price'),
                DB::raw('SUM(jobposts.maximum_budget) as total_earnings'),
                DB::raw('ROUND(AVG(reviews.rating) * 20, 1) as job_completion_rate')
            )
            ->groupBy('users.user_id')
            ->orderByDesc('total_earnings')
            ->limit(10)
            ->get();

        return response()->json($report);
    }

    public function topRatedArtisansReport()
    {
        $report = DB::table('users')
    ->join('profiles', 'users.user_id', '=', 'profiles.user_id')
    ->join('jobposts', 'users.user_id', '=', 'jobposts.user_id')
    ->select(
        'users.user_id',
        'users.user_name',
        'jobposts.category',
        DB::raw('ROUND(AVG(profiles.rating) / 20, 1) as rating'),
        DB::raw('COUNT(jobposts.jobpost_id) as completed_jobs'),
        DB::raw('ROUND(AVG(profiles.rating), 1) as satisfaction_rate')
    )
    ->groupBy('users.user_id', 'users.user_name', 'jobposts.category')
    ->orderByDesc('rating')
    ->get();


        return response()->json($report);
    }

   public function lowPerformanceUsersReport()
    {
        $report = DB::table('users')
            ->join('profiles', 'users.user_id', '=', 'profiles.user_id')
            ->leftJoin('reviews', 'users.user_id', '=', 'reviews.review_to')
            ->select(
                'users.user_name',
                'users.role_id as role',
                DB::raw('ROUND(AVG(reviews.rating), 1) as avg_rating'),
                DB::raw('COUNT(reviews.review_id) as flags'),
                DB::raw('MAX(reviews.review_comment) as last_reported_issue'),
                DB::raw("CASE WHEN AVG(reviews.rating) < 2.5 THEN 'Suspension' WHEN AVG(reviews.rating) < 3 THEN 'Review' ELSE 'Warning' END as action_required")
            )
            ->groupBy('users.user_id', 'users.user_name', 'users.role_id')
            ->having('avg_rating', '<', 3.5)
            ->orderBy('avg_rating')
            ->get();

        return response()->json($report);
    }

     public function monthlyActivityReport()
    {
        $report = DB::table('users')
            ->join('jobposts', 'users.user_id', '=', 'jobposts.user_id')
            ->select(
                DB::raw('MONTHNAME(jobposts.created_at) as month'),
                DB::raw('COUNT(DISTINCT users.user_id) as new_users'),
                DB::raw('COUNT(jobposts.jobpost_id) as jobs_posted'),
                DB::raw("SUM(CASE WHEN jobposts.status = 'completed' THEN 1 ELSE 0 END) as jobs_completed"),
                DB::raw('SUM(jobposts.maximum_budget) as total_earnings')
            )
            ->groupBy(DB::raw('MONTH(jobposts.created_at)'), DB::raw('MONTHNAME(jobposts.created_at)'))
            ->get();

        return response()->json($report);
    }

    public function locationBasedDemandReport()
    {
        $report = DB::table('jobposts')
            ->select(
                'location as city',
                DB::raw('COUNT(*) as jobs_posted'),
                DB::raw('MAX(category) as top_category'),
                DB::raw('COUNT(DISTINCT user_id) as active_artisans'),
                DB::raw('ROUND(COUNT(*) / COUNT(DISTINCT user_id), 1) as demand_supply_ratio')
            )
            ->groupBy('location')
            ->get();

        return response()->json($report);
    }
    
     public function topJobFinishersReport()
    {
        $report = DB::table('users')
            ->join('jobposts', 'users.user_id', '=', 'jobposts.user_id')
            ->where('jobposts.status', 'done')
            ->select(
                'users.user_name',
                DB::raw('COUNT(jobposts.jobpost_id) as completed_jobs'),
                DB::raw('SUM(jobposts.maximum_budget) as total_earnings')
            )
            ->groupBy('users.user_id', 'users.user_name')
            ->orderByDesc('completed_jobs')
            ->limit(10)
            ->get();

        return response()->json($report);
    }

}

