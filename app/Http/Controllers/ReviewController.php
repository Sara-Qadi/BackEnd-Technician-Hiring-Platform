<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

 use App\Models\JobPost;
use App\Models\Review;
use App\Models\User;
use App\Models\Proposal;
use App\Http\Resources\ReviewResource;
class ReviewController extends Controller
{
  
public function store(Request $request) 
{
    $request->validate([
        'rating' => 'required|numeric|min:1|max:5',
        'review_comment' => 'nullable|string',
        'review_to' => 'required|exists:users,user_id', 
    ]);

    $user = Auth::user();

    $jobPost = Jobpost::where('user_id', $user->user_id)
                ->where('status', 'completed')
                ->first();

    if (!$jobPost) {
        return response()->json([
            'message' => 'No completed jobpost found for this user.'
        ], 404);
    }

    $acceptedProposal = Proposal::where('jobpost_id', $jobPost->jobpost_id)
        ->where('status_agreed', 'accepted')
        ->first();

    if (!$acceptedProposal) {
        return response()->json([
            'message' => 'No accepted proposal found for this job.'
        ], 404);
    }

    if (
        $user->user_id !== $jobPost->user_id &&
        $user->user_id !== $acceptedProposal->tech_id
    ) {
        return response()->json([
            'message' => 'You are not authorized to review this job.'
        ], 403);
    }

    $existingReview = Review::where('jobpost_id', $jobPost->jobpost_id)
        ->where('review_by', $user->user_id)
        ->first();

    if ($existingReview) {
        return response()->json([
            'message' => 'You have already submitted a review for this job.'
        ], 409);
    }

    $review = Review::create([
        'jobpost_id'     => $jobPost->jobpost_id,
        'review_to'      => $request->review_to,  
        'review_by'      => $user->user_id,
        'rating'         => $request->rating,
        'review_comment' => $request->review_comment,
    ]);

    return response()->json([
        'message' => 'Review submitted successfully.',
        'review'  => $review
    ], 201);
}

    
   public function getUserReviews($user_id)
{
    $reviews = Review::with(['reviewer', 'jobPost'])
        ->where('review_to', $user_id)
        ->whereHas('jobPost', fn($q) => $q->where('status', 'completed'))
        ->latest() 
        ->get();

    return response()->json([
        'userName' => User::find($user_id)?->user_name ?? 'user',
        'reviews' => ReviewResource::collection($reviews)
    ]);
}


   public function getUserAverageRating($user_id)
{
    $reviews = Review::with('jobPost')
        ->where('review_to', $user_id)
        ->get();

    $user = User::find($user_id);

    $average = Review::where('review_to', $user_id)
        ->whereHas('jobPost', fn($q) => $q->where('status', 'completed'))
        ->avg('rating');

    return response()->json([
        'userName' => $user->name,
        'reviews' => $reviews,
        'average_rating' => round($average ?? 0, 2)
    ]);
}

 
 
}

