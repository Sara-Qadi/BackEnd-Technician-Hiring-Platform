<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Review;


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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'jobpost_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'jobpost_id');
    }

    // omar
    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'jobpost_id');
    }
    public function submission()
    {
        return $this->hasOne(Submission::class, 'submission_id');
    }
}




