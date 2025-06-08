<?php
namespace App\Http\Controllers;
use App\Models\JobPost;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function getAllUsers(Request $request) {
        $search = $request->query('search');
        $users = User::with('role')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('user_name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                });
            })
            ->get();
        return response()->json($users);
    }
    public function deleteUser($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return response()->json(['error' => 'Invalid user ID'], 400);
        }
        $user = User::where('user_id', $id)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['success' => 'User deleted successfully']);
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
    public function getAllJobPosts(Request $request)
    {
        $query = jobpost::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        }
        $jobPosts = $query->get();

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
    public function pendingRequestTechnician(Request $request)
    {
        $query = User::where('role_id', 3)
            ->where('is_approved', false);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%");
            });
        }

        $pendingTechnician = $query->get()->map(function ($user) {
            return [
                'user_id' => $user->user_id,
                'user_name' => $user->user_name,
                'phone' => $user->phone,
                'email' => $user->email,
                'created_at' => $user->created_at
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $pendingTechnician,
        ]);
    }
    public function acceptTechnician($user_id)
    {
        $user = User::where('user_id', $user_id)->first();

        if (!$user || $user->role_id != 3 || $user->is_approved) {
            return response()->json(['message' => 'You cannot accept this user'], 403);
        }

        $user->update(['is_approved' => true]);

        return response()->json(['message' => 'User accepted successfully']);
    }
    public function rejectTechnician($user_id)
    {
        $user = User::where('user_id', $user_id)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->role_id != 3 || $user->is_approved) {
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
