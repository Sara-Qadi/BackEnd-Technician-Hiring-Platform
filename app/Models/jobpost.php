<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    protected $table = 'jobpost';
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
    ];
    public function user(){
        return $this-> belongsTo(User::class, 'user_id');
    }
}