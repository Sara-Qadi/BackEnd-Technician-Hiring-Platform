<?php

namespace App\Http\Controllers;
//use App\Models\Submission;
use App\Models\JobPost;
use App\Models\Proposal;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;


class SubmissionController extends Controller
{
    public function __construct()
{
    $this->middleware('auth:sanctum')->only(['accept', 'reject']);
}

    public function accept($id){
        $user = auth()->user();


        $submission = Proposal::find($id);
        if (!$submission) {
            return response('proposal not found', 404);
        }
        if ($submission->JobPost->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($user->role->role_id != 2) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $submission->status_agreed = 'accepted'; 
        $submission->save();

        if ($submission->JobPost) {
            $submission->JobPost->status = 'in progress';
            $submission->JobPost->save();
        }

        // Notify the technician that their offer was accepted
        Notification::create([
            'user_id' => $submission->tech_id,
            'read_status' => 'unread',
            'type' => 'bid_response',
            'message' => 'Your offer has been accepted by the job owner.',
        ]);

        return response()->json([
            'message' => 'accepted this proposal',
            'data' => $submission
        ], 200);
    }

    public function reject($id){
        $user = auth()->user();
        $submission = Proposal::find($id);

        if (!$submission) {
            return response('proposal not found', 404);
        }
        if ($submission->JobPost->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($user->role->role_id != 2) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $submission->status_agreed = 'rejected'; 
        $submission->save();
        $job = $submission->jobPost;
        if ($job) {
            $job->status = 'pending';
            $job->save();
        }
        // حذف البروبوزل المرتبط
        //$submission->delete();

        // Notify the technician that their offer was rejected
        Notification::create([
            'user_id' => $submission->tech_id,
            'read_status' => 'unread',
            'type' => 'bid_response',
            'message' => 'Your offer has been rejected by the job owner.',
        ]);

        return response()->json([
            'message' => 'rejected this proposal',
            'data' => $submission
        ], 200);
    }

    //sara
public function getTotalSubmissions()
{
    $total = Proposal::count();
    return response()->json(['total_submissions' => $total]);
}


}
