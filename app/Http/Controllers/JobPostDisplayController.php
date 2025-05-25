<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;

class JobPostDisplayController extends Controller
{
    public function FilterJobPost(Request $request)
    {
        $query = JobPost::query();

        if ($request->has('category') && is_array($request->category)) {
            $query->whereIn('category', $request->category);
        }

        if ($request->has('minimum_budget')) {
            $query->where('budget', '>=', $request->minimum_budget);
        }

        if ($request->has('maximum_budget')) {
            $query->where('budget', '<=', $request->maximum_budget);
        }

        if ($request->has('location')) {
            $query->where('location', $request->location);
        }

        $job_posts = $query->get();

        return response()->json($job_posts);
    }
}



