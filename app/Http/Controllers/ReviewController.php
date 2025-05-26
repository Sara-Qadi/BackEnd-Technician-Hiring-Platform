<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Models\JobPost;
use App\Models\Review;
use App\Models\User;
class ReviewController extends Controller
{
  
public function store(Request $request)
{
    $request->validate([
        'jobpost_id' => 'required|exists:job_posts,id',
        'rating' => 'required|numeric|min:1|max:5',
        'comment' => 'nullable|string',
        'reviewer_id' => 'required|exists:users,id'
    ]);

    $jobPost = JobPost::findOrFail($request->jobpost_id);

    if ($jobPost->status !== 'done') {
        return response()->json([
            'message' => 'You can only review a job that is marked as done.'
        ], 403);
    }

    $review = Review::create([
        'jobpost_id' => $jobPost->id,
        'review_to' => $jobPost->user_id,
        'reviewer_id' => $request->review_by,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return response()->json([
        'message' => 'Review created successfully.',
        'review' => $review
    ], 201);
}


    
    public function getUserReviews($user_id)
    {
        $reviews = Review::with(['reviewer', 'jobPost'])
            ->where('review_to', $user_id)
            ->whereHas('jobPost', fn($q) => $q->where('status', 'done'))
            ->get();

        return response()->json($reviews);
    }

    public function getUserAverageRating($user_id)
    {
        $average = Review::where('review_to', $user_id)
            ->whereHas('jobPost', fn($q) => $q->where('status', 'done'))
            ->avg('rating');

        return response()->json(['average_rating' => round($average, 2)]);
    }
}
