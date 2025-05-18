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
//jobpost routes
Route::get('/jobpost/all', 'App\Http\Controllers\JobpostController@all');//done
Route::get('/jobpost/count', 'App\Http\Controllers\JobpostController@count');//done
Route::get('/jobpost/showpost/{id}', 'App\Http\Controllers\JobpostController@showpost');//done
Route::get('/jobpost/delete/{id}', 'App\Http\Controllers\JobpostController@delete');//ما جربته لسا
Route::post('/jobpost/add', 'App\Http\Controllers\JobpostController@add');//ما جربته لسا
Route::post('/jobpost/update{id}', 'App\Http\Controllers\JobpostController@update');//ما جربته لسا

//submission routes
Route::get('/submission/accept/{id}', 'App\Http\Controllers\SubmissionController@accept');
Route::get('/submission/reject/{id}', 'App\Http\Controllers\SubmissionController@reject');

