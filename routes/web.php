<?php

use Illuminate\Support\Facades\Route;

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

