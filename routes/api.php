<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('admin/allUsers', [AdminController::class, 'getAllUsers']);
Route::delete('admin/deleteUsers/{id}', [AdminController::class, 'deleteUser']);
Route::put('admin/updateUsers/{user_id}', [AdminController::class, 'updateUser']);
Route::post('admin/createUsers', [AdminController::class, 'createUser']);
Route::get('admin/getAllJobPosts', [AdminController::class, 'getAllJobPosts']);
Route::delete('admin/deleteJobPost/{id}', [AdminController::class, 'deleteJobPost']);
Route::get('admin/pendingTechnician', [AdminController::class, 'pendingRequestTechnician']);
Route::put('admin/acceptTechnician/{id}', [AdminController::class, 'acceptTechnician']);
Route::delete('admin/rejectTechnician/{id}', [AdminController::class, 'rejectTechnician']);
Route::post('/admin/report', [AdminController::class, 'reportUser']);

