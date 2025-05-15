<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppliesFor extends Model
{
    protected $table = 'appliesfor';
    public $timestamps = false;
    public $incrementing = false; 

    protected $fillable = [
        'ProposalId',
        'UserId',
        'JobPostId',
        'Rating',
        'ReviewComment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'JobPostId');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'ProposalId');
    }
}