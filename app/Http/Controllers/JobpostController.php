<?php

namespace App\Http\Controllers;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JobpostController extends Controller
{
    protected $jobpost;
    public function __construct()
    {
        $this->jobpost = new JobPost();
    }
   public function allPosts(){
        //return JobPost::orderBy('deadline', 'asc')->get();
        $jobposts = DB::table('jobposts')
            ->join('users', 'jobposts.user_id', '=', 'users.user_id')
            ->select('jobposts.*', 'users.user_id', 'users.user_name')
            ->orderBy('deadline', 'asc')->get();
        return response()->json($jobposts);
    }
    public function allPostsforTech(){
        //return JobPost::orderBy('deadline', 'asc')->get();
        $jobposts = DB::table('jobposts')
            ->join('users', 'jobposts.user_id', '=', 'users.user_id')
            ->select('jobposts.*', 'users.user_id', 'users.user_name')
            ->where('jobposts.status', 'pending')
            ->orderBy('deadline', 'asc')->get();
        return response()->json($jobposts);
    }
    
    public function filterJobs($title){
    return JobPost::where('title', 'like', "%$title%")->get();
    }

    public function count(){
        return JobPost::where('jobpost_id','>',0)->count();
    }
    
    public function showUserposts($id){
        $user = User::where('user_id', $id)->first();
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

        $jobposts = DB::table('jobposts')
        ->join('users', 'jobposts.user_id', '=', 'users.user_id')
        ->where('jobposts.user_id', $id)
        ->select('jobposts.*', 'users.user_id', 'users.user_name')
        ->orderBy('jobposts.deadline', 'asc')
        ->get();

    return response()->json($jobposts);
    }
   
    /*public function showpost($id){
        $job=JobPost::find($id);
        if (!$job) {
        return response('job not found', 404);
    }
        return $job;
    }*/
    public function showpost($id)
{
    $jobpost = DB::table('jobposts')
        ->join('users', 'jobposts.user_id', '=', 'users.user_id')
        ->where('jobposts.jobpost_id', $id)
        ->select('jobposts.*', 'users.user_id', 'users.user_name')
        ->first();

    if (!$jobpost) {
        return response()->json(['message' => 'Job post not found'], 404);
    }

    return response()->json($jobpost);
}
    
    /*public function deletePost(Request $request){
        $job = JobPost::find($request->id);

        if (!$job) {
            return response('Job not found', 404);
        }

        $job->delete();
        return response('Job deleted successfully');
    }*/

    public function deletePost($id){
        $job = JobPost::find($id);

        if (!$job) {
            return response()->json('Job not found', 404);
        }
        $result=$job->delete();
        return response()->json('Job deleted successfully');
    }
    public function addPost(Request $request){
        
        return $this->jobpost->create($request->all());
       /* $job = new JobPost();
        $job->title = $request->title;
        $job->description = $request->description;
        $job->user_id = $request->user_id;
        $job->save();
        return response('Job added successfully');*/
        /*$post = $request->validate([
        'title' => 'required|string',
        'category' => 'required|string|in:Carpenter,Plumber,Electrician,Painter,Mason,Roofing,Mechanic,Welder,Tiler,ACTechnician,CameraInstaller',
        'minimum_budget' => 'required|numeric|min:0',
        'maximum_budget' => 'required|numeric|gte:minimum_budget',
        'deadline' => 'required|date|after:today',
        'attachments' => 'nullable|array',
        'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
        'location' => 'required|string',
        'description' => 'required|string|min:50',
        'user_id' => 'required|exists:users,user_id'
    ]);

    $post['status'] = 'pending';

    // تخزين المرفقات إن وُجدت
    if ($request->hasFile('attachments')) {
        $paths = [];
        foreach ($request->file('attachments') as $file) {
            $paths[] = $file->store('attachments', 'public');
        }
        $post['attachments'] = json_encode($paths);
    }

    $job = JobPost::create($post);

    return response()->json([
        'message' => 'Job created successfully',
        'data' => $job
    ], 201);*/
    }
    /*public function updatePost(Request $request, $id){
        //$job = JobPost::find($request->id);
         $validated = $request->validate([
        'title' => 'required|string|max:255',
        'category' => 'required|string',
        'minimum_budget' => 'required|numeric',
        'maximum_budget' => 'required|numeric',
        'deadline' => 'required|date',
        'location' => 'required|string',
        'description' => 'required|string',
        // إذا في مرفقات، أضف القاعدة هنا
    ]);

    $job = JobPost::findOrFail($id);
    $job->update($validated);

    return response()->json(['message' => 'Job updated successfully']);
        /*$job->title = $request->title;
        $job->description = $request->description;
        $job->user_id = $request->user_id;
        $job->save();
        return response('Job updated successfully';)*/
        /* $data = $request->all();
        if ($request->hasFile('attachments')) {
            $path = $request->file('attachments')->store('attachments', 'public');
            $data['attachments'] = $path;
        }
        $job->update($data);
        return response()->json([
            'message' => 'Job updated successfully',
            'jobpost' => $job
        ]);*
    }*/
   /* public function updatePost(Request $request, $id)
{
    
    $jobpost=$this->jobpost->find($id);
    $jobpost->update($request->all());
    return response()->json([
        'message' => 'Job updated successfully',
        'data' => $jobpost
    ]);
    /*$validated = $request->validate([
        'title' => 'required|string|max:255',
        'category' => 'required|string',
        'minimum_budget' => 'required|numeric',
        'maximum_budget' => 'required|numeric',
        'deadline' => 'required|date',
        'location' => 'required|string',
        'description' => 'required|string',
        'attachments' => 'nullable|array',
        'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $job = JobPost::findOrFail($id);

    // إذا فيه مرفقات، خزنهم
    if ($request->hasFile('attachments')) {
        $paths = [];
        foreach ($request->file('attachments') as $file) {
            $paths[] = $file->store('attachments', 'public');
        }
        $validated['attachments'] = json_encode($paths);
    }

    $job->update($validated);

    return response()->json(['message' => 'Job updated successfully']);*
}*/




public function updatePost(Request $request, $id)
{
    /*$validated = $request->validate([
        'title' => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'minimum_budget' => 'required|numeric|min:1',
        'maximum_budget' => 'required|numeric|min:1|gte:minimum_budget',
        'deadline' => 'required|date|after_or_equal:today',
        'location' => 'required|string|max:255',
        'description' => 'required|string|min:50',
    ]);


    $jobpost = Jobpost::findOrFail($id);

    $jobpost->update($request->only(
        'title', 'category', 'minimum_budget', 'maximum_budget', 'deadline', 'location', 'description'
    ));
    

    return response()->json([
        'message' => 'Job updated successfully',
        'data' => $jobpost
    ]);*/
     return $this->jobpost->update($request->all());
}


    public function downloadfiles(Request $request)
{
    $request->validate([
        'jobpost_id' => 'required|exists:jobposts,jobpost_id',
    ]);

    $jobPost = JobPost::where('jobpost_id', $request->jobpost_id)->first();

    /******* */
    // تأكدي من وجود الملف
    /*if (!$jobPost || !$jobPost->attachments) {
        return response()->json(['message' => 'Attachment not found'], 404);
    }*/

    // في حال كان الحقل يحتوي أكثر من ملف، يمكن استخراج أول ملف مثلاً:
    $attachmentFile = json_decode($jobPost->attachments, true)[0] ?? $jobPost->attachments;

    $filePath = 'jobposts/' . $attachmentFile;

    if (!Storage::disk('public')->exists($filePath)) {
        return response()->json(['message' => 'File does not exist on server'], 404);
    }

    return Storage::disk('public')->download($filePath, $attachmentFile);
}

}
