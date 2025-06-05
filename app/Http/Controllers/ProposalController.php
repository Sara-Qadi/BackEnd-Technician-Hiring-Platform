<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Proposal;

class ProposalController extends Controller
{
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
     public function countProposalsByJobPost($id)
    {
        return Proposal::where('jobpost_id', $id)->where('jobpost_id','>',0)->count();
    }

    public function makeNewProposals(Request $request)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'status_agreed' => 'boolean',
            'description_proposal' => 'nullable|string',
            'tech_id' => 'required|exists:users,user_id',
            'jobpost_id' => 'required|exists:jobposts,jobpost_id',
        ]);

        $proposal = Proposal::create($request->all());

        return response()->json([
            'message' => 'Proposal created successfully',
            'data' => $proposal
        ], 201);
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

        $request->validate([
            'price' => 'nullable|numeric|min:0',
            'status_agreed' => 'nullable|boolean',
            'description_proposal' => 'nullable|string',
            //'tech_id' => 'nullable|exists:users,id',
            //'jobpost_id' => 'nullable|exists:jobposts,id',
            'tech_id' => 'required|exists:users,user_id',
            'jobpost_id' => 'required|exists:jobposts,jobpost_id',
        ]);

        $proposal->update($request->all());

        return response()->json([
            'message' => 'Proposal updated successfully',
            'data' => $proposal
        ]);
    }

    public function deleteProposal($id)
    {
        $proposal = Proposal::find($id);

        if (!$proposal) {
            return response()->json(['message' => 'Proposal not found'], 404);
        }

        $proposal->delete();

        return response()->json(['message' => 'Proposal deleted successfully']);
    }
}
