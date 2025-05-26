<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobpostController;
use App\Http\Controllers\SubmissionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');





// Notification routes
Route::get('/notifications/{userId}', function ($userId) {
    return [
        [
            'notification_id' => 201,
            'user_id' => $userId,
            'message' => 'Dummy notification 1',
            'read_status' => 'unread',
        ],
        [
            'notification_id' => 202,
            'user_id' => $userId,
            'message' => 'Dummy notification 2',
            'read_status' => 'read',
        ],
    ];
});

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
