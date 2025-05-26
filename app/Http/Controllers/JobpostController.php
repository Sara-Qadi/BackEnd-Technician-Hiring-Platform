<?php

namespace App\Http\Controllers;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobpostController extends Controller
{
   public function allPosts(){
        return JobPost::orderBy('deadline', 'asc')->get();
    }
    
    public function filterJobs($title){
    return JobPost::where('title', 'like', "%$title%")->get();
    }

    public function count(){
        return JobPost::where('jobpost_id','>',0)->count();
    }
    
    public function showpost(Request $request){
        $job=User::find($request->id);
        if (!$job) {
        return response('User not found', 404);
    }
        return $job->jobposts;
    }

    public function deletePost(Request $request){
        $job = JobPost::find($request->id);

        if (!$job) {
            return response('Job not found', 404);
        }

        $job->delete();
        return response('Job deleted successfully');
    }
    public function addPost(Request $request){
       /* $job = new JobPost();
        $job->title = $request->title;
        $job->description = $request->description;
        $job->user_id = $request->user_id;
        $job->save();
        return response('Job added successfully');*/
        $post = $request->validate([
            'title' => 'required|string',
            'category' => 'required|string|in:carpenter,plumber,electrician,painter', //بضيف باقي الكاتيجوريز
            'minimum_budget' => 'required|numeric|min:0',
            'maximum_budget' => 'required|numeric|gte:minimum_budget',
            'deadline' => 'required|date|after:today',
            'attachments' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'location' => 'required|string',
            'description' => 'required|string|min:50',
            'user_id' => 'required|exists:users,user_id'
        ]);
        $post['status'] = 'pending';
        if ($request->hasFile('attachments')) {
            $path = $request->file('attachments')->store('attachments', 'public');
            $post['attachments'] = $path;
        }
        $job = JobPost::create($post);
        return response()->json([
            'message' => 'Job created successfully',
            'data' => $job
        ], 201);
    }
    public function updatePost(Request $request, $id){
        //$job = JobPost::find($request->id);
        $job = JobPost::find($id);
        if (!$job) {
            return response('Job not found', 404);
        }
        $request->validate([
            'title' => 'required|string',
            'category' => 'required|string|in:carpenter,plumber,electrician,painter', //بضيف باقي الكاتيجوريز
            'minimum_budget' => 'required|numeric|min:0',
            'maximum_budget' => 'required|numeric|gte:minimum_budget',
            'deadline' => 'required|date|after:today',
            'attachments' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'location' => 'required|string',
            'description' => 'required|string|min:50',
            'user_id' => 'required|exists:users,user_id'
        ]);
        /*$job->title = $request->title;
        $job->description = $request->description;
        $job->user_id = $request->user_id;
        $job->save();
        return response('Job updated successfully';)*/
         $data = $request->all();
        if ($request->hasFile('attachments')) {
            $path = $request->file('attachments')->store('attachments', 'public');
            $data['attachments'] = $path;
        }
        $job->update($data);
        return response()->json([
            'message' => 'Job updated successfully',
            'jobpost' => $job
        ]);
    }
    public function downloadfiles(Request $request)
{
    $request->validate([
        'jobpost_id' => 'required|exists:jobposts,jobpost_id',
    ]);

    $jobPost = JobPost::where('jobpost_id', $request->jobpost_id)->first();

    // تأكدي من وجود الملف
    if (!$jobPost || !$jobPost->attachments) {
        return response()->json(['message' => 'Attachment not found'], 404);
    }

    // في حال كان الحقل يحتوي أكثر من ملف، يمكن استخراج أول ملف مثلاً:
    $attachmentFile = json_decode($jobPost->attachments, true)[0] ?? $jobPost->attachments;

    $filePath = 'jobposts/' . $attachmentFile;

    if (!Storage::disk('public')->exists($filePath)) {
        return response()->json(['message' => 'File does not exist on server'], 404);
    }

    return Storage::disk('public')->download($filePath, $attachmentFile);
}

}
