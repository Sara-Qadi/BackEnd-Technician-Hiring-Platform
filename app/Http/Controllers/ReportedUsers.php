<?php

namespace App\Http\Controllers;

use App\Models\Report;

class ReportedUsers
{
    public function store(Request $request)
    {
        $request->validate([
            'reportable_type' => 'required|string',
            'reportable_id'   => 'required|integer',
            'reason'          => 'required|string|max:255',
            'description'     => 'nullable|string',
        ]);

        $allowed = [
            \App\Models\User::class,
            \App\Models\Jobpost::class,
        ];

        if (!in_array($request->reportable_type, $allowed)) {
            return response()->json([
                'message' => 'Invalid report target'
            ], 400);
        }

        $class = $request->reportable_type;

        $target = $class::findOrFail($request->reportable_id);

        // منع الإبلاغ عن نفسه (لو كان User)
        if ($class === \App\Models\User::class && $target->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot report yourself'
            ], 403);
        }

        $report = $target->reports()->create([
            'reporter_id' => auth()->id(),
            'reason'      => $request->reason,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Report submitted successfully',
            'data'    => $report
        ], 201);
    }

    public function index()
    {
        return Report::with(['reporter', 'reportable'])
            ->latest()
            ->paginate(20);
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:reviewed,accepted,rejected'
        ]);

        $report->update([
            'status'      => $request->status,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Report updated successfully',
            'data' => $report
        ]);
    }




}
