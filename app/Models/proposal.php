<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $table = 'proposals';

    protected $fillable = [
        'price',
        'status_agreed',
        'description_proposal',
        'tech_id',
        'jobpost_id',
    ];

    public function technician()
    {
        return $this->belongsTo(User::class, 'tech_id');
    }

    public function jobpost()
    {
        return $this->belongsTo(Jobpost::class, 'jobpost_id');
    }
}
