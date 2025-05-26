<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobpostController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');





// Notification routes
Route::get('/notifications/{userId}', [NotificationsController::class, 'index']);
Route::patch('/notifications/{notificationId}/read', [NotificationsController::class, 'markAsRead']);
Route::delete('/notifications/{notificationId}', [NotificationsController::class, 'destroy']);

// Profile routes
Route::get('/profiles/{user_id}', [ProfileController::class, 'show']);
Route::put('/profiles/{user_id}', [ProfileController::class, 'update']);
Route::post('/profiles', [ProfileController::class, 'store']);

// Jobpost routes
Route::get('/jobpost/allposts', [JobpostController::class, 'allPosts']);
Route::get('/jobpost/countposts', [JobpostController::class, 'countPosts']);
Route::get('/jobpost/showpost/{id}', [JobpostController::class, 'showpost']);
Route::delete('/jobpost/deletepost/{id}', [JobpostController::class, 'deletePost']);
Route::post('/jobpost/addpost', [JobpostController::class, 'addPost']);
Route::put('/jobpost/updatepost/{id}', [JobpostController::class, 'updatePost']);
Route::post('/jobposts/download', [JobPostController::class, 'downloadfiles']);

// Submission routes
Route::post('/submission/accept', [SubmissionController::class, 'acceptProposal']);
Route::post('/submission/reject', [SubmissionController::class, 'rejectProposal']);

// Proposal routes
Route::get('/proposal/allproposals', [ProposalController::class, 'returnAllProposals']);
Route::post('/proposal/addproposal', [ProposalController::class, 'makeNewProposals']);
Route::get('/proposal/showproposal/{id}',[ProposalController::class, 'show']);
Route::put('/proposal/updateproposal/{id}', [ProposalController::class, 'updateProposal']);
Route::delete('/proposal/deleteproposal/{id}', [ProposalController::class, 'deleteProposal']);

// User routes
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show'])->where('id', '[0-9]+');
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update'])->where('id', '[0-9]+');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->where('id', '[0-9]+');
Route::get('/users/technicians', [UserController::class, 'getTechnicians']);
Route::get('/users/owners', [UserController::class, 'getJobOwners']);
Route::get('/users/admins', [UserController::class, 'getAdmins']);

//Auth routes
Route::middleware('auth:sanctum')->get('/test-token', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

