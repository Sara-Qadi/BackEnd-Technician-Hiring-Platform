<?php

namespace App\Http\Controllers;
use App\Models\JobPost;
use App\Models\User;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JobpostController extends Controller
{
    protected $jobpost;
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only([
        'deletePost', 'updatePost', 'addPost','updatestatus'
    ]);
        $this->jobpost = new JobPost();
    }
   public function allPosts(){
        $jobposts = DB::table('jobposts')
            ->join('users', 'jobposts.user_id', '=', 'users.user_id')
            ->select('jobposts.*', 'users.user_id', 'users.user_name')
            ->orderBy('deadline', 'asc')->get();
        return response()->json($jobposts);
    }

    public function allPostsforTech(){
        $jobposts = DB::table('jobposts')
            ->join('users', 'jobposts.user_id', '=', 'users.user_id')
            ->select('jobposts.*', 'users.user_id', 'users.user_name')
            ->where('jobposts.status', 'pending')
            ->orderBy('deadline', 'asc')->get();
        return response()->json($jobposts);
    }
    public function allPendingPosts($id){
        $user = User::where('user_id', $id)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $jobposts = DB::table('jobposts')
            ->join('users', 'jobposts.user_id', '=', 'users.user_id')
            ->where('jobposts.user_id', $id)
            ->where('jobposts.status', 'pending')
            ->select('jobposts.*', 'users.user_id', 'users.user_name')
            ->orderBy('jobposts.deadline', 'asc')
            ->get();

        return response()->json($jobposts);
    }
    public function allonProgressPosts($id){
        $user = User::where('user_id', $id)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $jobposts = DB::table('jobposts')
            ->join('users', 'jobposts.user_id', '=', 'users.user_id')
            ->where('jobposts.user_id', $id)
            ->where('jobposts.status', 'in progress')
            ->select('jobposts.*', 'users.user_id', 'users.user_name')
            ->orderBy('jobposts.deadline', 'asc')
            ->get();

    return response()->json($jobposts);
    }
    public function allCompletedPosts($id){
        $user = User::where('user_id', $id)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $jobposts = DB::table('jobposts')
            ->join('users', 'jobposts.user_id', '=', 'users.user_id')
            ->where('jobposts.user_id', $id)
            ->where('jobposts.status', 'completed')
            ->select('jobposts.*', 'users.user_id', 'users.user_name')
            ->orderBy('jobposts.deadline', 'asc')
            ->get();

        return response()->json($jobposts);
    }

   public function filterJobs($title){
     return JobPost::where('title', 'like', "%$title%")->where('jobposts.status', 'pending')->get();
}
    public function count(){
        return JobPost::where('jobpost_id','>',0)->count();
    }

    public function showUserposts($id){
        //$id = auth()->id();
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

    public function deletePost($id){

        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Not Authenticated'], 401);
        }

        $job = JobPost::findOrFail($id);
        if ($job->user_id !== $user->user_id) {
            return response()->json(['message' => 'You do not have permission to update this job.'], 403);
        }

        if ($user->role->role_id == 3) {
         return response()->json(['message' => 'Unauthorized'], 403);
        }
        $result=$job->delete();
        return response()->json('Job deleted successfully');
    }

    public function addPost(Request $request){
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Not Authenticated'], 401);
        }
        if ($user->role->role_id != 2) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $validated = $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'minimum_budget' => 'required|numeric|min:0',
            'maximum_budget' => 'required|numeric|gte:minimum_budget',
            'deadline' => 'required|date|after:today',
        //'attachments' => 'nullable|array',
        //'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'location' => 'required|string',
            'description' => 'required|string|min:50',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $validated['user_id'] = auth()->id(); // 🔒 اربط البوست بالمستخدم المسجل
        $validated['status'] = 'pending';
        $filenames = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->storeAs('attachments',$originalName, 'public'); // يخزن في storage/app/public/attachments
                $filenames[] = $originalName;
            }
            $validated['attachments'] = json_encode($filenames); // نحفظها كنص JSON
        }
        $job = JobPost::create($validated);

        return response()->json([
            'message' => 'Job created successfully',
            'data' => $job
        ], 201);    
    }


public function updatePost(Request $request, $id)
{
    $user = auth()->user();
    if (!$user) {
        return response()->json(['message' => 'Not Authenticated'], 401);
    }

    $job = JobPost::findOrFail($id);
    if ($job->user_id !== $user->user_id) {
        return response()->json(['message' => 'You do not have permission to update this job.'], 403);
    }

    if ($user->role->role_id == 3) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'minimum_budget' => 'required|numeric|min:0',
        'maximum_budget' => 'required|numeric|gte:minimum_budget',
        'deadline' => 'required|date|after:today',
        'location' => 'nullable|string|max:255',
        'description' => 'nullable|string',
    ]);
    //dd($validated);
    // التحديث
    $job->update($validated);
    $job->refresh();
    return response()->json([
        'message' => 'Job updated successfully',
        'data' => $job
    ]);
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
 public function updatestatus($id){
     /*$user = auth()->user();
  if (!$user) {
        return response()->json(['message' => 'Not Authenticated'], 401);
    }*/

    $job = JobPost::findOrFail($id);
    /*if ($job->user_id !== $user->user_id) {
        return response()->json(['message' => 'You do not have permission to update this job.'], 403);
    }

    if ($user->role->role_id != 2) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }*/
    $job['status'] = 'completed';
    $job->save();
    return response()->json(['message' => 'Job status updated successfully', 'job' => $job]);
 }
 public function downloadAttachmentByName($filename)
{
    $filePath = 'attachments/' . $filename;

    if (Storage::disk('public')->exists($filePath)) {
        return Storage::disk('public')->download($filePath);
    } else {
        return response()->json(['message' => 'File not found'], 404);
    }
}

//sara
public function getTotalJobPosts()
{
    $count = JobPost::count();
    return response()->json(['total_posts' => $count]);
}

public function getMonthlyJobPostCounts()
{
    $counts = DB::table('jobposts')
        ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total'))
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->orderBy(DB::raw('MONTH(created_at)'))
        ->get();

    return response()->json($counts);
}


//sara
public function completedJobsForTechnician()
{
    $jobs = DB::table('jobposts')
        ->join('users', 'jobposts.user_id', '=', 'users.user_id')
        ->where('jobposts.status', 'completed')
        ->select('jobposts.*', 'users.user_id', 'users.user_name')
        ->orderBy('jobposts.deadline', 'desc')
        ->get();

    return response()->json($jobs);
}


public function getJobStatusCounts()
{
    $inProgress = JobPost::where('status', 'in progress')->count();
    $completed = JobPost::where('status', 'completed')->count();

    return response()->json([
        'in_progress' => $inProgress,
        'completed' => $completed
    ]);
}


    public function getJobownerIdBytheJobpostId($jobpost_id)
    {
    $jobOwner = DB::table('jobposts')
        ->where('jobpost_id', $jobpost_id)
        ->select('user_id')
        ->first();

    if (!$jobOwner) {
        return response()->json(['message' => 'Job owner not found'], 404);
    }

    return response()->json(['jobowner_id' => $jobOwner->user_id]);
    }  



    public function getTechIdBytheJobpostId($jobpost_id)
    {
        $proposal = Proposal::where('jobpost_id', $jobpost_id)->get();

        if (!$proposal) {
            return response()->json(['message' => 'Proposal not found'], 404);
        }

        return response()->json(['proposals' => $proposal]);
    }
}

