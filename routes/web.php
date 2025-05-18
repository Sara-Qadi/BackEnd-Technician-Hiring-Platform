<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProfileController;

Route::prefix('api')->group(function () {
    Route::get('/notifications/{userId}', [NotificationsController::class, 'index']);
    Route::patch('/notifications/{notificationId}/read', [NotificationsController::class, 'markAsRead']);
    Route::delete('/notifications/{notificationId}', [NotificationsController::class, 'destroy']);

    Route::get('/profiles/{user_id}', [ProfileController::class, 'show']);
Route::put('/profiles/{user_id}', [ProfileController::class, 'update']);
Route::post('/profiles', [ProfileController::class, 'store']);
});


Route::get('/', function () {
    return view('welcome');
});
