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
        $profile = Profile::find($userId);

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $profile->update($request->only(['cv', 'rating']));

        return response()->json(['message' => 'Profile updated', 'profile' => $profile]);
    }

    public function store(Request $request)
    {
        $profile = Profile::create($request->only(['user_id', 'photo', 'cv', 'rating']));

        return response()->json(['message' => 'Profile created', 'profile' => $profile]);
    }
}
