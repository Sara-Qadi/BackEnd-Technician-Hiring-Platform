<?php

namespace App\Http\Controllers;
use App\Models\Submission;
use App\Models\JobPost;
use App\Models\Proposal;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;


class SubmissionController extends Controller
{

    public function accept(Request $request){
    $submission = Submission::find($request->id);
    if (!$submission) {
        return response('Submission not found', 404);
    }

   $submission->status_agreed = 1;
    $submission->save();

    if ($submission->jobpost) {
        $submission->jobpost->status = 'in progress';
        $submission->jobpost->save();
    }

// Notify the technician that their offer was accepted
Notification::create([
    'user_id' => $submission->tech_id,
    'read_status' => 'unread',
    'type' => 'bid_response',
    'message' => 'Your offer has been accepted by the job owner.',
]);

    return redirect()->back();
    }
    public function reject(Request $request){
    $submission = Submission::find($request->id);

    if (!$submission) {
        return response('Submission not found', 404);
    }

    // تعيين status_agreed إلى 0
    $submission->status_agreed = 0;
    $submission->save();

    // حذف البروبوزل المرتبط
    $submission->proposals()->delete();

    // Notify the technician that their offer was rejected
        Notification::create([
        'user_id' => $submission->tech_id,
        'read_status' => 'unread',
        'type' => 'bid_response',
        'message' => 'Your offer has been rejected by the job owner.',
    ]);

    return redirect()->back();
    }

}
