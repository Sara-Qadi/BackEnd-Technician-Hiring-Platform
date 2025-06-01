<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
      // جلب كل المستخدمين
      public function index()
      {
          $users = User::all();
          return response()->json($users);
      }
//=================================================================================
      public function show($id)
      {
            $user = User::find($id);

             if (!$user) {
             return response()->json(['message' => 'User not found'], 404);
             }

               return response()->json($user);
    }
//=================================================================================
    public function store(Request $request)
{
    $validated = $request->validate([
        'user_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:20',
        'password' => 'required|string|min:6',
        'country' => 'required|string|max:50',
        'role_id' => 'required|exists:roles,role_id',
    ]);

    $validated['password'] = Hash::make($validated['password']);

    $user = User::create($validated);

    return response()->json([
        'message' => 'User created successfully',
        'user' => $user
    ], 201);
}
//=================================================================================
public function update(Request $request, $id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $validated = $request->validate([
        'user_name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $id . ',user_id',
        'phone' => 'sometimes|string|max:20',
        'password' => 'sometimes|string|min:6',
        'country' => 'sometimes|string|max:50',
        'role_id' => 'sometimes|exists:roles,role_id',
    ]);

    // إذا فيه باسورد بدنا نعمله هاش
    if (isset($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    }

    $user->update($validated);

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user
    ]);
}
//=================================================================================
public function destroy($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $user->delete();

    return response()->json(['message' => 'User deleted successfully']);
}
//=================================================================================
public function getTechnicians()
{
    $technicians = User::where('role_id', 3)->get();

    return response()->json($technicians);
}
//=================================================================================
public function getJobOwners()
{
    $owners = User::where('role_id', 2)->get();

    return response()->json($owners);
}
//=================================================================================
public function getAdmins()
{
    $admins = User::where('role_id', 1)->get();

    return response()->json($admins);
}


public function updateName(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $user = auth()->user();

    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $user->user_name = $request->name;
    $user->save();

    return response()->json(['message' => 'Name updated successfully', 'user_name' => $user->user_name]);
}

//sara
public function countPendingApprovals()
{
    $pendingCount = User::where('is_approved', 0)->count();
    return response()->json(['pending_approvals' => $pendingCount]);
}


}

