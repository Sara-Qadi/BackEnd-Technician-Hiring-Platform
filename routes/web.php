<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ReportsController;

use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobpostController;
use App\Http\Controllers\SubmissionController;

// Route::prefix('api')->group(function () {
//     // Notification routes
//     Route::get('/notifications/{userId}', [NotificationsController::class, 'index']);
//     Route::patch('/notifications/{notificationId}/read', [NotificationsController::class, 'markAsRead']);
//     Route::delete('/notifications/{notificationId}', [NotificationsController::class, 'destroy']);

//     // Profile routes
//     Route::get('/profiles/{user_id}', [ProfileController::class, 'show']);
//     Route::put('/profiles/{user_id}', [ProfileController::class, 'update']);
//     Route::post('/profiles', [ProfileController::class, 'store']);

//     // Jobpost routes
//     Route::get('/jobpost/all', [JobpostController::class, 'all']);
//     Route::get('/jobpost/count', [JobpostController::class, 'count']);
//     Route::get('/jobpost/showpost/{id}', [JobpostController::class, 'showpost']);
//     Route::delete('/jobpost/delete/{id}', [JobpostController::class, 'delete']);
//     Route::post('/jobpost/add', [JobpostController::class, 'add']);
//     Route::post('/jobpost/update/{id}', [JobpostController::class, 'update']);

//     // Submission routes (use POST with ID in request body)
//     Route::post('/submission/accept', [SubmissionController::class, 'accept']);
//     Route::post('/submission/reject', [SubmissionController::class, 'reject']);
// });


Route::get('/', function () {
    return view('welcome');

    
});



