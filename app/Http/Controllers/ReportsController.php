<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\AllReportsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function jobCompletionReport()
    {
        $data = DB::table('jobposts')
            ->selectRaw('MONTH(created_at) as month_number')
            ->selectRaw('MONTHNAME(MIN(created_at)) as month')
            ->selectRaw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_jobs')
            ->selectRaw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_jobs')
            ->selectRaw('SUM(CASE WHEN status = "in progress" THEN 1 ELSE 0 END) as in_progress_jobs')
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
    $data = DB::table('users')
        ->join('jobposts', 'users.user_id', '=', 'jobposts.user_id')
        ->join('reviews', 'users.user_id', '=', 'reviews.review_to')
        ->where('jobposts.status', 'done') 
        ->selectRaw('
            users.user_id,
            MAX(users.user_name) as user_name,
            COUNT(jobposts.jobpost_id) as completed_jobs,
            ROUND(AVG(jobposts.maximum_budget), 2) as avg_job_price,
            SUM(jobposts.maximum_budget) as total_earnings,
            ROUND(AVG(reviews.rating) * 20, 1) as job_completion_rate
        ')
        ->groupBy('users.user_id')
        ->orderByDesc('total_earnings')
        ->limit(10)
        ->get();

    return response()->json([
        'headers' => ['User ID', 'User Name', 'Completed Jobs', 'Avg Job Price', 'Total Earnings', 'Job Completion Rate'],
        'data' => $data->map(function ($row) {
            return [
                $row->user_id,
                $row->user_name,
                $row->completed_jobs,
                $row->avg_job_price,
                $row->total_earnings,
                $row->job_completion_rate . '%',
            ];
        }),
    ]);
}




   public function topRatedArtisansReport()
{
    $data = DB::table('users')
        ->join('profiles', 'users.user_id', '=', 'profiles.user_id')
        ->join('jobposts', 'users.user_id', '=', 'jobposts.user_id')
        ->selectRaw('users.user_id')
        ->selectRaw('MAX(users.user_name) as user_name')
        ->selectRaw('jobposts.category')
        ->selectRaw('ROUND(AVG(profiles.rating) / 20, 1) as rating')
        ->selectRaw('COUNT(jobposts.jobpost_id) as completed_jobs')
        ->selectRaw('ROUND(AVG(profiles.rating), 1) as satisfaction_rate')
        ->groupBy('users.user_id', 'jobposts.category')
        ->orderByDesc('rating')
        ->get();

    return response()->json([
        'headers' => ['User ID', 'User Name', 'Category', 'Rating', 'Completed Jobs', 'Satisfaction Rate'],
        'data' => $data->map(fn($row) => [
            $row->user_id,
            $row->user_name,
            $row->category,
            $row->rating,
            $row->completed_jobs,
            $row->satisfaction_rate,
        ]),
    ]);
}


   public function lowPerformanceUsersReport()
{
    $data = DB::table('users')
        ->join('profiles', 'users.user_id', '=', 'profiles.user_id')
        ->leftJoin('reviews', 'users.user_id', '=', 'reviews.review_to')
        ->selectRaw('users.user_name')
        ->selectRaw('users.role_id as role')
        ->selectRaw('ROUND(AVG(reviews.rating), 1) as avg_rating')
        ->selectRaw('COUNT(reviews.review_id) as flags')
        ->selectRaw('MAX(reviews.review_comment) as last_reported_issue')
        ->selectRaw("CASE WHEN AVG(reviews.rating) < 2.5 THEN 'Suspension' WHEN AVG(reviews.rating) < 3 THEN 'Review' ELSE 'Warning' END as action_required")
        ->groupBy('users.user_id', 'users.user_name', 'users.role_id')
        ->havingRaw('avg_rating < 3.5')
        ->orderBy('avg_rating')
        ->get();

    return response()->json([
        'headers' => ['User Name', 'Role', 'Avg Rating', 'Flags', 'Last Reported Issue', 'Action Required'],
        'data' => $data->map(fn($row) => [
            $row->user_name,
            $row->role,
            $row->avg_rating,
            $row->flags,
            $row->last_reported_issue,
            $row->action_required,
        ]),
    ]);
}


   public function monthlyActivityReport()
{
    $data = DB::table('jobposts')
        ->selectRaw('MONTH(created_at) as month_number')
        ->selectRaw('MONTHNAME(MIN(created_at)) as month')
        ->selectRaw('COUNT(DISTINCT user_id) as new_users')
        ->selectRaw('COUNT(jobpost_id) as jobs_posted')
        ->selectRaw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as jobs_completed")
        ->selectRaw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as jobs_cancelled")
        ->selectRaw("SUM(CASE WHEN status = 'in progress' THEN 1 ELSE 0 END) as jobs_in_progress")
        ->selectRaw('ROUND(SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) * 100 / COUNT(*), 1) as completion_rate')
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->orderBy('month_number')
        ->get();

    return response()->json([
        'headers' => ['Month', 'New Users', 'Jobs Posted', 'Completed Jobs', 'Cancelled Jobs', 'In Progress Jobs', 'Completion Rate'],
        'data' => $data->map(fn($row) => [
            $row->month,
            $row->new_users,
            $row->jobs_posted,
            $row->jobs_completed,
            $row->jobs_cancelled,
            $row->jobs_in_progress,
            $row->completion_rate . '%',
        ]),
    ]);
}


   public function locationBasedDemandReport()
{
    $data = DB::table('jobposts')
        ->selectRaw('location as city')
        ->selectRaw('COUNT(*) as jobs_posted')
        ->selectRaw('MAX(category) as top_category')
        ->selectRaw('COUNT(DISTINCT user_id) as active_artisans')
        ->selectRaw('ROUND(COUNT(*) / COUNT(DISTINCT user_id), 1) as demand_supply_ratio')
        ->groupBy('location')
        ->get();

    return response()->json([
        'headers' => ['City', 'Jobs Posted', 'Top Category', 'Active Artisans', 'Demand/Supply Ratio'],
        'data' => $data->map(fn($row) => [
            $row->city,
            $row->jobs_posted,
            $row->top_category,
            $row->active_artisans,
            $row->demand_supply_ratio,
        ]),
    ]);
}


   public function topJobFinishersReport()
{
    $data = DB::table('users')
        ->join('jobposts', 'users.user_id', '=', 'jobposts.user_id')
        ->where('jobposts.status', 'open')
        ->selectRaw('users.user_name')
        ->selectRaw('COUNT(jobposts.jobpost_id) as completed_jobs')
        ->selectRaw('SUM(jobposts.maximum_budget) as total_earnings')
        ->groupBy('users.user_id', 'users.user_name')
        ->orderByDesc('completed_jobs')
        ->limit(10)
        ->get();

    return response()->json([
        'headers' => ['User Name', 'Completed Jobs', 'Total Earnings'],
        'data' => $data->map(fn($row) => [
            $row->user_name,
            $row->completed_jobs,
            $row->total_earnings,
        ]),
    ]);
}


public function exportAllReports()
{
    return Excel::download(new AllReportsExport, 'all_reports.xlsx');
}

}

