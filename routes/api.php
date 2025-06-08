<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobpostController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ForgotPasswordController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/user/role/{id}',[UserController::class, 'getroleid']);

//admin
Route::middleware(['auth:sanctum','admin'])->group(function () {
    Route::get('admin/allUsers', [AdminController::class, 'getAllUsers']);
    Route::delete('admin/users/{id}', [AdminController::class, 'deleteUser']);
    Route::put('admin/updateUsers/{user_id}', [AdminController::class, 'updateUser']);
    Route::get('admin/getAllJobPosts', [AdminController::class, 'getAllJobPosts']);
    Route::delete('admin/deleteJobPost/{id}', [AdminController::class, 'deleteJobPost']);
    Route::get('admin/pendingTechnician', [AdminController::class, 'pendingRequestTechnician']);
    Route::put('admin/acceptTechnician/{user_id}', [AdminController::class, 'acceptTechnician']);
    Route::delete('admin/rejectTechnician/{user_id}', [AdminController::class, 'rejectTechnician']);
});



Route::post('/admin/report', [AdminController::class, 'reportUser']);

//notification

Route::prefix('notifications')->group(function () {
    // GET /api/notifications/{userId}
    Route::get('{userId}', [NotificationsController::class, 'index']);

    // POST /api/notifications
    Route::post('', [NotificationsController::class, 'store']);

    // PUT /api/notifications/mark-as-read/{notificationId}
    Route::put('mark-as-read/{notificationId}', [NotificationsController::class, 'markAsRead']);

    // DELETE /api/notifications/{notificationId}
    Route::delete('{notificationId}', [NotificationsController::class, 'destroy']);
});

// Profile routes

Route::middleware('auth:sanctum')->post('/user/name', [UserController::class, 'updateName']);



// Jobpost routes
Route::middleware('auth:sanctum')->group(function (){
    Route::delete('/jobpost/deletepost/{id}', [JobpostController::class, 'deletePost']);
    Route::post('/jobpost/addpost', [JobpostController::class, 'addPost']);
    Route::put('/jobpost/updatepost/{id}', [JobpostController::class, 'updatePost']);
    Route::put('/submission/accept/{id}', [SubmissionController::class, 'accept']);
    Route::put('/submission/reject/{id}', [SubmissionController::class, 'reject']);
});
Route::put('/jobpost/updatestatus/{id}', [JobpostController::class, 'updatestatus']);
Route::get('/jobpost/allposts', [JobpostController::class, 'allPosts']);
Route::get('/jobpost/countposts', [JobpostController::class, 'countPosts']);
Route::get('/jobpost/showpost/{id}', [JobpostController::class, 'showpost']);
Route::get('/jobpost/showuserposts/{id}', [JobpostController::class, 'showUserposts']);
Route::get('/jobpost/allPostsforTech',[JobPostController::class ,'allPostsforTech']);


Route::get('/attachments/download/{filename}', [JobPostController::class, 'downloadAttachmentByName']);

// search for jobpost omar
Route::get('/jobpost/filterJobs/{title}', [JobpostController::class, 'filterJobs']);


// Submission routes

// Proposal routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/proposal/addproposal/{jobpost_id}', [ProposalController::class, 'makeNewProposals']);
    Route::put('/proposal/updateproposal/{id}', [ProposalController::class, 'updateProposal']);
    Route::delete('/proposal/deleteproposal/{id}', [ProposalController::class, 'deleteProposal']);
    
});
Route::get('/proposal/showproposal/{id}',[ProposalController::class, 'show']);
Route::get('/proposal/allproposals', [ProposalController::class, 'returnAllProposals']);
Route::get('/proposals/jobpost/{id}', [ProposalController::class, 'returnProposalsByJobPost']);
Route::get('/proposals/jobpost/count/{id}', [ProposalController::class, 'countProposalsByJobPost']);
Route::get('/proposals/getTechNameById/{id}', [ProposalController::class, 'getTechNameById']);


// User routes
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show'])->where('id', '[0-9]+');
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update'])->where('id', '[0-9]+');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->where('id', '[0-9]+');
Route::get('/users/technicians', [UserController::class, 'getTechnicians']);
Route::get('/users/owners', [UserController::class, 'getJobOwners']);
Route::get('/users/admins', [UserController::class, 'getAdmins']);


// review routes
Route::post('/reviews', [ReviewController::class,'store']);
Route::get('/users/{user_id}/reviews', [ReviewController::class,'getUserReviews']);
Route::get('/users/{user_id}/average-rating', [ReviewController::class,'getUserAverageRating']);

//reports routes
Route::get('/reports/completion', [ReportsController::class,'jobCompletionReport']);
Route::get('/reports/earnings', [ReportsController::class,'earningsReport']);
Route::get('/reports/top-rated', [ReportsController::class,'topRatedArtisansReport']);
Route::get('/reports/low-performance', [ReportsController::class, 'lowPerformanceUsersReport']);
Route::get('/reports/monthly-activity', [ReportsController::class, 'monthlyActivityReport']);
Route::get('/reports/top-job-finishers', [ReportsController::class,'topJobFinishersReport']);
Route::get('/reports/location-demand', [ReportsController::class, 'locationBasedDemandReport']);
Route::get('/reports/export-all-reports', [ReportsController::class, 'exportAllReports']);


//Auth routes
Route::middleware('auth:sanctum')->get('/test-token', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'store']);
        Route::post('/profile/update', [ProfileController::class, 'update']);
});
Route::put('/updatejo/{id}', [UserController::class, 'updateUser']);
Route::get('/profile/{id}', [ProfileController::class, 'showById']);



Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


// Route for testing middleware based on token ability
Route::middleware(['auth:sanctum', 'ability:admin'])->get('/admin/dashboard', function (Request $request) {
    return response()->json([
        'message' => 'Hello Admin! You are authorized.',
        'user' => $request->user(),
    ]);
});



Route::middleware('auth:sanctum')->get('/debug/token', function (Request $request) {
    return response()->json([
        'tokenAbilities' => $request->user()->currentAccessToken()->abilities,
        'tokenCanAdmin' => $request->user()->tokenCan('admin'),
        'tokenCanTechnician' => $request->user()->tokenCan('technician'),
        'tokenCanOwner' => $request->user()->tokenCan('jobowner'),

    ]);
});

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


Route::post('/submission/accept', [SubmissionController::class, 'accept']);
Route::post('/submission/reject', [SubmissionController::class, 'reject']);



//dashboard
Route::get('/dashboard/total-posts', [JobpostController::class, 'getTotalJobPosts']);
Route::get('/dashboard/total-submissions', [SubmissionController::class, 'getTotalSubmissions']);
Route::get('/dashboard/jobposts-per-month', [JobpostController::class, 'getMonthlyJobPostCounts']);
Route::get('/users/pending-approvals', [UserController::class, 'countPendingApprovals']);

//massages
Route::post('/messages/store', [MessagesController::class, 'storeMessage']);
Route::get('/messages/get-conversation/{sender_id}/{receiver_id}', [MessagesController::class, 'getConversation']);
Route::get('/messages/get-user-conversations/{user_id}', [MessagesController::class, 'getUserConversations']);

//forget password
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ForgotPasswordController::class, 'reset']);
