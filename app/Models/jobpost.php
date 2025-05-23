<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobPost extends Model
{
    use HasFactory;
    protected $table = 'jobposts';
    protected $primaryKey = 'jobpost_id';

    protected $fillable = [
        'title',
        'category',
        'maximum_budget',
        'minimum_budget',
        'deadline',
        'status',
        'attachments',
        'location',
        'description',
        'user_id',
    ];
    public function user(){
        return $this-> belongsTo(User::class, 'user_id');
    }

    // omar
    public function submission(){
        return $this-> hasOne(Submission::class, 'submission_id');
    }
    //--------------------------------------------------------
}



