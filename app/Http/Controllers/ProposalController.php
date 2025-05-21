<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;

class ProposalController extends Controller
{
    public function returnAllProposals()
    {
        return response()->json(Proposal::all());
    }

    public function makeNewProposals(Request $request)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'status_agreed' => 'boolean',
            'description_proposal' => 'nullable|string',
            'tech_id' => 'required|exists:users,id',
            'jobpost_id' => 'required|exists:jobposts,id',
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
            'tech_id' => 'nullable|exists:users,id',
            'jobpost_id' => 'nullable|exists:jobposts,id',
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
