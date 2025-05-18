<?php

namespace App\Http\Controllers;
use App\Models\Submission;
use App\Models\JobPost;
use App\Models\Proposal;
use Illuminate\Http\Request;

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

    return redirect()->back();
    }

}
