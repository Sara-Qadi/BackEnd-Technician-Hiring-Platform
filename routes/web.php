<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('admin/allUsers', [AdminController::class, 'getAllUsers']);
Route::delete('admin/deleteUsers/{id}', [AdminController::class, 'deleteUser']);
