<?php

namespace App\Http\Controllers;
use App\Models\JobPost;
use App\Models\User;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JobpostController extends Controller
{
    protected $jobpost;
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only([
            'deletePost', 'updatePost', 'addPost'
        ]);

        $this->jobpost = new JobPost();
    }

    public function allPosts()
    {
        $jobposts = DB::table('jobposts')
            ->join('users', 'jobposts.user_id', '=', 'users.user_id')
            ->select('jobposts.*', 'users.user_id', 'users.user_name')
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json($jobposts);
    }

    public function allPostsforTech()
    {
        $jobposts = DB::table('jobposts')
            ->join('users', 'jobposts.user_id', '=', 'users.user_id')
            ->select('jobposts.*', 'users.user_id', 'users.user_name')
            ->where('jobposts.status', 'pending')
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json($jobposts);
    }

    public function allPendingPosts($id)
    {
        $user = User::where('user_id', $id)->first();
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        $jobposts = DB::table('jobposts')
            ->join('users', 'jobposts.user_id', '=', 'users.user_id')
            ->where('jobposts.user_id', $id)
            ->where('jobposts.status', 'pending')
            ->select('jobposts.*', 'users.user_id', 'users.user_name')
            ->orderBy('jobposts.deadline', 'asc')
            ->get();

        return response()->json($jobposts);
    }

    public function allonProgressPosts($id)
    {
        $user = User::where('user_id', $id)->first();
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        $jobposts = DB::table('jobposts')
            ->join('users', 'jobposts.user_id', '=', 'users.user_id')
            ->where('jobposts.user_id', $id)
            ->where('jobposts.status', 'in progress')
            ->select('jobposts.*', 'users.user_id', 'users.user_name')
            ->orderBy('jobposts.deadline', 'asc')
            ->get();

        return response()->json($jobposts);
    }

    public function allCompletedPosts($id)
    {
        $user = User::where('user_id', $id)->first();
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        $jobposts = DB::table('jobposts')
            ->join('users', 'jobposts.user_id', '=', 'users.user_id')
            ->where('jobposts.user_id', $id)
            ->where('jobposts.status', 'completed')
            ->select('jobposts.*', 'users.user_id', 'users.user_name')
            ->orderBy('jobposts.deadline', 'asc')
            ->get();

        return response()->json($jobposts);
    }

    public function filterJobs($title)
    {
        return JobPost::where('title', 'like', "%$title%")
            ->where('jobposts.status', 'pending')
            ->get();
    }

    public function count()
    {
        return JobPost::where('jobpost_id', '>', 0)->count();
    }

    public function showUserposts($id)
    {
        $user = User::where('user_id', $id)->first();
        if (!$user) return response()->json(['message' => 'User not found'], 404);

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

    public function deletePost($id)
    {
        $user = auth()->user();
        if (!$user) return response()->json(['message' => 'Not Authenticated'], 401);

        $job = JobPost::findOrFail($id);

        if ($job->user_id !== $user->user_id) {
            return response()->json(['message' => 'You do not have permission to update this job.'], 403);
        }

        if ($user->role->role_id != 2) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $job->delete();
        return response()->json('Job deleted successfully');
    }

    public function addPost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'minimum_budget' => 'required|numeric|min:0',
            'maximum_budget' => 'required|numeric|gte:minimum_budget',
            'deadline' => 'required|date|after:today',
            'location' => 'required|string',
            'description' => 'required|string|min:50',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        $filenames = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $file->storeAs('attachments', $originalName, 'public');
                $filenames[] = $originalName;
            }
            $validated['attachments'] = json_encode($filenames);
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
        if (!$user) return response()->json(['message' => 'Not Authenticated'], 401);

        $job = JobPost::findOrFail($id);

        if ($job->user_id !== $user->user_id) {
            return response()->json(['message' => 'You do not have permission to update this job.'], 403);
        }

        if ($user->role->role_id != 2) {
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

        $attachmentFile = json_decode($jobPost->attachments, true)[0] ?? $jobPost->attachments;
        $filePath = 'jobposts/' . $attachmentFile;

        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['message' => 'File does not exist on server'], 404);
        }

        return Storage::disk('public')->download($filePath, $attachmentFile);
    }

    public function updatestatus($id)
    {
        $job = JobPost::findOrFail($id);
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

    public function completedJobsForTechnician()
    {
        $techId = auth()->id();

        $jobs = DB::table('jobposts')
            ->join('proposals', 'proposals.jobpost_id', '=', 'jobposts.jobpost_id')
            ->join('users', 'jobposts.user_id', '=', 'users.user_id')
            ->where('jobposts.status', 'completed')
            ->where('proposals.tech_id', $techId)
            ->select('jobposts.*', 'users.user_id', 'users.user_name')
            ->distinct()
            ->orderBy('jobposts.deadline', 'desc')
            ->get();

        return response()->json($jobs);
    }

    public function completedJobsForTechnicianById($techId)
    {
        $jobs = DB::table('jobposts')
            ->join('proposals', 'proposals.jobpost_id', '=', 'jobposts.jobpost_id')
            ->join('users', 'jobposts.user_id', '=', 'users.user_id') // job owner
            ->where('jobposts.status', 'completed')
            ->where('proposals.tech_id', $techId)
            ->select('jobposts.*', 'users.user_id', 'users.user_name')
            ->distinct()
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
