<?php

namespace App\Http\Controllers;
use App\Models\JobPost;
use App\Models\LUser;
use Illuminate\Http\Request;

class JobpostController extends Controller
{
   public function all(){
        return JobPost::all();
    }
    public function count(){
        return JobPost::where('jobpost_id','>',0)->count();
    }
    public function showpost(Request $request){
        $job=LUser::find($request->id);
        if (!$job) {
        return response('User not found', 404);
    }
        return $job->jobposts;
    }

    public function delete(Request $request){
    $job = JobPost::find($request->id);

    if (!$job) {
        return response('Job not found', 404);
    }

    $job->delete();
    return response('Job deleted successfully');
    }
    public function add(Request $request){
        $job = new JobPost();
        $job->title = $request->title;
        $job->description = $request->description;
        $job->user_id = $request->user_id;
        $job->save();
        return response('Job added successfully');
    }
    public function update(Request $request){
        $job = JobPost::find($request->id);
        if (!$job) {
            return response('Job not found', 404);
        }
        $job->title = $request->title;
        $job->description = $request->description;
        $job->user_id = $request->user_id;
        $job->save();
        return response('Job updated successfully');
    }
}
