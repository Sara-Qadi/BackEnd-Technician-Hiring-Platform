<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // نحدد نوع الصلاحية حسب الدور
        $ability = match ($user->role_id) {
            1 => 'admin',
            2 => 'jobowner',
            3 => 'technician',
            default => 'guest'
        };

        $token = $user->createToken('login_token', [$ability])->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }


    public function register(Request $request)
    {
         $request->validate([
             'user_name' => 'required|string|max:255',
             'email' => 'required|email|unique:users,email',
             'phone' => 'required|string|max:20',
             'password' => 'required|string|min:6',
             'country' => 'required|string|max:50',
             'role_id' => 'required|exists:roles,role_id'
         ]);
     //if role_id == tech then $isApproved = false; otherwise $isApproved = true;
         if ($request->role_id == 3) {
            $isApproved = false;
        } else {
            $isApproved = true;
        }
        
         
         $user = User::create([
             'user_name' => $request->user_name,
             'email' => $request->email,
             'phone' => $request->phone,
             'country' => $request->country,
             'role_id' => $request->role_id,
             'password' => Hash::make($request->password),
             'is_approved' => $isApproved,
         ]);
     
         // نحدد نوع الصلاحية حسب الدور
        $ability = match ($user->role_id) {
            1 => 'admin',
            2 => 'jobowner',
            3 => 'technician',
            default => 'guest'
        };

        $token = $user->createToken('login_token', [$ability])->plainTextToken;
     
         return response()->json([
             'token' => $token,
             'user' => $user
         ], 201);
    }
}
