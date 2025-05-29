<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($userId)
    {
        $profile = Profile::with('user')->find($userId);

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        return response()->json($profile);
    }

   public function update(Request $request, $userId)
{
    $profile = Profile::where('user_id', $userId)->first();

    if (!$profile) {
        return response()->json(['message' => 'Profile not found'], 404);
    }

    // Validate input
    $validated = $request->validate([
        'specialty' => 'sometimes|string|max:255',
        'description' => 'sometimes|string',
        'cv' => 'sometimes|file|mimes:pdf,doc,docx|max:2048',
    ]);

    if ($request->hasFile('cv')) {
        $cvFile = $request->file('cv');
        $cvPath = $cvFile->store('cvs', 'public'); // stores in storage/app/public/cvs

        // Delete old CV file if you want here (optional)

        $profile->cv = $cvPath;
    }

    if (isset($validated['specialty'])) {
        $profile->specialty = $validated['specialty'];
    }

    if (isset($validated['description'])) {
        $profile->description = $validated['description'];
    }

    $profile->save();

    return response()->json(['message' => 'Profile updated', 'profile' => $profile]);
}


    public function store(Request $request)
    {
        $profile = Profile::create($request->only(['user_id', 'photo', 'cv', 'rating']));

        return response()->json(['message' => 'Profile created', 'profile' => $profile]);
    }
}
