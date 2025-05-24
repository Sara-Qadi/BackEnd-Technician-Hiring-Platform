<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobpostController;
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
Route::get('/jobpost/all', [JobpostController::class, 'all']);
Route::get('/jobpost/count', [JobpostController::class, 'count']);
Route::get('/jobpost/showpost/{id}', [JobpostController::class, 'showpost']);
Route::delete('/jobpost/delete/{id}', [JobpostController::class, 'delete']);
Route::post('/jobpost/add', [JobpostController::class, 'add']);
Route::post('/jobpost/update/{id}', [JobpostController::class, 'update']);

// Submission routes
Route::post('/submission/accept', [SubmissionController::class, 'accept']);
Route::post('/submission/reject', [SubmissionController::class, 'reject']);


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

