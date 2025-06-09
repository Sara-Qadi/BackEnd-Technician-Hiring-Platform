<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Jobpost;
class Review extends Model
{
    protected $primaryKey = 'review_id';
    protected $fillable = [
        'review_by',
        'review_to',
        'jobpost_id',
        'rating',
        'review_comment',
    ];

  
public function reviewer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(User::class, 'review_by', 'user_id');
}

public function reviewee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(User::class, 'review_to', 'user_id');
}

   
  
    public function jobpost(): BelongsTo
    {
        return $this->belongsTo(Jobpost::class, 'jobpost_id');
    }
}
