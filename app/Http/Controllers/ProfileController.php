<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $profile = Profile::where('user_id', $user->user_id)->first();

        return response()->json([
            'user_id'    => $user->user_id,
            'user_name'  => $user->user_name,
            'email'      => $user->email,
            'phone'      => $user->phone,
            'country'    => $user->country,

            'specialty'  => $profile?->specialty,
            'description'=> $profile?->description,
            'cv'         => $profile?->cv,
            'rating'     => $profile?->rating,
            'photo'      => $profile?->photo,
        ]);
    }

    public function showById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $profile = Profile::where('user_id', $user->user_id)->first();

        return response()->json([
            'user_id'    => $user->user_id,
            'user_name'  => $user->user_name,
            'email'      => $user->email,
            'phone'      => $user->phone,
            'country'    => $user->country,

            'specialty'  => $profile?->specialty,
            'description'=> $profile?->description,
            'cv'         => $profile?->cv,
            'rating'     => $profile?->rating,
            'photo'      => $profile?->photo,
        ]);
    }


    public function update(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401);
        }

        $profile = Profile::firstOrCreate(['user_id' => $user->user_id]);

        $validated = $request->validate([
            'name'        => 'nullable|string|max:255',
            'specialty'   => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cv'          => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->filled('name')) {
            $user->user_name = $request->input('name');
            $user->save();
        }

        $isTechnician = ((int)$user->role_id === 3);

        if ($isTechnician) {
            if ($request->has('specialty')) {
                $profile->specialty = $request->input('specialty');
            }

            if ($request->has('description')) {
                $profile->description = $request->input('description');
            }

            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('cvs', 'public');
                $profile->cv = $cvPath;
            }
        }

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $profile->photo = $photoPath;
        }

        $profile->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $profile
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'specialty'   => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cv'          => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $existingProfile = Profile::where('user_id', $user->user_id)->first();
        if ($existingProfile) {
            return response()->json(['message' => 'Profile already exists'], 400);
        }

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }

        $imagePath = null;
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('profile_pictures', 'public');
        }

        $profile = Profile::create([
            'user_id'     => $user->user_id,
            'specialty'   => $validated['specialty'] ?? null,
            'description' => $validated['description'] ?? null,
            'cv'          => $cvPath,
            'photo'       => $imagePath,
            'rating'      => 0,
        ]);

        return response()->json(['message' => 'Profile created', 'profile' => $profile]);
    }
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->update([
            'user_name' => $request->user_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'country'   => $request->country,
        ]);

        $profile = Profile::firstOrCreate(['user_id' => $user->user_id]);

        if ((int)$user->role_id === 3) {
            if ($request->has('description')) {
                $profile->description = $request->description;
            }
            if ($request->has('specialty')) {
                $profile->specialty = $request->specialty;
            }
        }

        $profile->save();

        return response()->json([
            'message' => 'User and profile updated successfully.',
            'user'    => $user,
            'profile' => $profile
        ]);
    }
}
