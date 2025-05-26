<?php
namespace App\Http\Controllers;
use App\Models\JobPost;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function getAllUsers(){
        $users = User::all();
        return response()->json($users);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function updateUser(Request $request, $user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $request->validate([
            'user_name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user_id . ',user_id',
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'role_id' => 'nullable|integer|exists:roles,role_id',
        ]);

        $user->user_name = $request->input('user_name', $user->user_name);
        $user->email = $request->input('email', $user->email);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->phone = $request->input('phone', $user->phone);
        $user->country = $request->input('country', $user->country);
        $user->role_id = $request->input('role_id', $user->role_id);

        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'role_id' => 'required|integer|exists:roles,role_id',
        ]);

        $user = new User();
        $user->user_name = $request->input('user_name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->phone = $request->input('phone');
        $user->country = $request->input('country');
        $user->role_id = $request->input('role_id');
        $user->save();

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }


    public function getAllJobPosts()
    {
        $jobPosts = jobpost::all();

        return response()->json([
            'success' => true,
            'data' => $jobPosts,
        ]);
    }

    public function deleteJobPost($id)
    {
        $jobPost = jobpost::find($id);

        if (!$jobPost) {
            return response()->json([
                'success' => false,
                'message' => 'Job post not found',
            ], 404);
        }

        $jobPost->delete();

        return response()->json([
            'success' => true,
            'message' => 'Job post deleted successfully',
        ]);
    }

    public function pendingRequestTechnician() {
        $pendingTechnician = User::where('role_id', 3)
            ->where('is_approved', false)->get();

        return response()->json([
            'success' => true,
            'data' => $pendingTechnician,
        ]);
    }

    public function acceptTechnician($id)
    {
        $user = User::find($id);
        if($user->role_id != 3 || $user->is_approved) {
            return response()->json(['message' => 'You cannot accept this user'], 403);
        }
        $user->is_approved = true;
        $user->save();
        return response()->json(['message' => 'User accepted successfully']);
    }
    public function rejectTechnician($id)
    {
        $user = User::find($id);
        if(! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        if($user->is_approved) {
            return response()->json(['message' => 'User already accepted, cannot reject'], 403);
        }
        $user->delete();
        return response()->json(['message' => 'User request rejected and deleted successfully']);
    }

    public function reportUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'reported_user_id' => 'required|exists:users,user_id',
            'reason' => 'required|string|max:255',
            'report_type' => 'required|string|max:100',
            'jobpost_id' => 'nullable|exists:jobposts,jobpost_id',
        ]);

        $data = $request->only([
            'user_id',
            'reported_user_id',
            'reason',
            'report_type',
        ]);

        if ($request->has('jobpost_id')) {
            $data['jobpost_id'] = $request->input('jobpost_id');
        }

        $report = Report::create($data);

        return response()->json([
            'message' => 'Report added successfully',
            'report' => $report,
        ], 201);
    }
}
