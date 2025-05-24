<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportsController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/reviews', [ReviewController::class,'store']);
Route::get('/users/{user_id}/reviews', [ReviewController::class,'getUserReviews']);
Route::get('/users/{user_id}/average-rating', [ReviewController::class,'getUserAverageRating']);
Route::get('/reports/completion', [ReportsController::class,'jobCompletionReport']);
Route::get('/reports/earnings', [ReportsController::class,'earningsReport']);
Route::get('/reports/top-rated', [ReportsController::class,'topRatedArtisansReport']);
Route::get('/reports/low-performance', [ReportsController::class, 'lowPerformanceUsersReport']);
Route::get('/reports/monthly-activity', [ReportsController::class, 'monthlyActivityReport']);
Route::get('/reports/top-job-finishers', [ReportsController::class,'topJobFinishersReport']);
Route::get('/reports/location-demand', [ReportsController::class, 'locationBasedDemandReport']);