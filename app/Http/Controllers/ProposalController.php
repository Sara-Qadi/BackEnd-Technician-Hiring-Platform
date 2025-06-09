<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\JobPost;
use App\Models\Proposal;
use App\Models\User;


class ProposalController extends Controller
{
    public function __construct()
{
    $this->middleware('auth:sanctum')->only([
        'makeNewProposals', 'updateProposal', 'deleteProposal'
    ]);
}
    public function returnAllProposals()
    {
        return response()->json(Proposal::all());
    }
    
    public function returnProposalsByJobPost($id)
    {
        //$proposals = Proposal::where('jobpost_id', $id)->get();
        //return response()->json($proposals);
        $proposals = DB::table('proposals')
        ->join('users', 'proposals.tech_id', '=', 'users.user_id')
        ->select('proposals.*', 'users.user_name', 'users.country')
        ->where('proposals.jobpost_id', $id)->get();
        return response()->json($proposals);
    }
    public function returnProposalsforJO($id){
        $proposals=DB::table('proposals')
        ->join('jobposts', 'proposals.jobpost_id', '=', 'jobposts.jobpost_id')
        ->where('jobposts.user_id', $id)
        ->select('proposals.*', 'jobposts.title', 'jobposts.deadline')
        ->get();
        return response()->json($proposals);
    }
    public function returnPendingProposalsforJO($id)
{
    $proposals = DB::table('proposals')
        ->join('jobposts', 'proposals.jobpost_id', '=', 'jobposts.jobpost_id')
        ->where('jobposts.user_id', $id)
        ->where('proposals.status_agreed', 'pending') 
        ->select('proposals.*', 'jobposts.title', 'jobposts.deadline')
        ->get();

    return response()->json($proposals);
}
public function returnAcceptedProposalsforJO($id)
{
    $proposals = DB::table('proposals')
        ->join('jobposts', 'proposals.jobpost_id', '=', 'jobposts.jobpost_id')
        ->where('jobposts.user_id', $id)
        ->where('proposals.status_agreed', 'accepted') 
        ->select('proposals.*', 'jobposts.title', 'jobposts.deadline')
        ->get();

    return response()->json($proposals);
}
    public function countAllProposalsforJO($id){
        return DB::table('proposals')
            ->join('jobposts', 'proposals.jobpost_id', '=', 'jobposts.jobpost_id')
            ->where('jobposts.user_id', $id)
            ->count();
    }
    public function getJobPostswithProposals($id)
{
    $jobPosts = JobPost::where('user_id', $id)
        ->has('proposals') // فقط الوظائف التي لديها بروبوزلز
        ->get();

    return response()->json($jobPosts);
}
public function countJobPostswithProposals($id)
{
    $jobPosts = JobPost::where('user_id', $id)
        ->has('proposals') // فقط الوظائف التي لديها بروبوزلز
        ->count();

    return response()->json($jobPosts);
}

     public function countProposalsByJobPost($id)
    {
        return Proposal::where('jobpost_id', $id)->where('jobpost_id','>',0)->count();
    }

   public function makeNewProposals(Request $request, $jobpost_id)
{
    $user = auth()->user();

     /*if ($user->role->role_id != 3) {
        return response()->json(['message' => 'Unauthorized'], 403);}*/

    $request->validate([
        'price' => 'required|numeric|min:0',
        'description_proposal' => 'nullable|string',
    ]);

    $data = $request->only(['price', 'description_proposal']);
    $data['jobpost_id'] = $jobpost_id;
    $data['tech_id'] = $user->user_id;
    $data['status_agreed'] = 'pending';

    $proposal = Proposal::create($data);

    $jobPost = \App\Models\JobPost::find($jobpost_id);
    if ($jobPost) {
        Notification::create([
            'user_id' => $jobPost->user_id,
            'read_status' => "unread",
            'type' => 'proposal',
            'message' => 'You received a new proposal for your job post.',
        ]);
    }

    return response()->json([
        'message' => 'Proposal created successfully',
        'data' => $proposal
    ], 201);
}


    public function getTechNameById($tech_id)
    {
        $tech = User::find($tech_id);
        if (!$tech) {
            return response()->json(['message' => 'Tech not found'], 404);
        }
        return response()->json($tech->user_name);
    }
    


    public function show($id)
    {
        $proposal = Proposal::find($id);

        if (!$proposal) {
            return response()->json(['message' => 'Proposal not found'], 404);
        }

        return response()->json($proposal);
    }


    public function updateProposal(Request $request, $id)
    {
        $proposal = Proposal::find($id);

        if (!$proposal) {
            return response()->json(['message' => 'Proposal not found'], 404);
        }
        if ($proposal->tech_id !== auth()->user()->user_id) {
           return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($user->role->role_id != 3) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

        $request->validate([
            'price' => 'nullable|numeric|min:0',
            'status_agreed' => 'nullable|boolean',
            'description_proposal' => 'nullable|string',
            //'tech_id' => 'nullable|exists:users,id',
            //'jobpost_id' => 'nullable|exists:jobposts,id',
            //'tech_id' => 'required|exists:users,user_id',
            //'jobpost_id' => 'required|exists:jobposts,jobpost_id',
        ]);

        $proposal->update($request->only([
    'price', 'status_agreed', 'description_proposal'
]));

        return response()->json([
            'message' => 'Proposal updated successfully',
            'data' => $proposal
        ]);
    }

    public function deleteProposal($id)
    {
        $proposal = Proposal::find($id);

        if ($user->role->role_id != 3) {
        return response()->json(['message' => 'Unauthorized'], 403);}
        if (!$proposal) {
            return response()->json(['message' => 'Proposal not found'], 404);
        }
        if ($proposal->tech_id !== auth()->user()->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $proposal->delete();

        return response()->json(['message' => 'Proposal deleted successfully']);
    }
}
