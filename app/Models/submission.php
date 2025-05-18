<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable = [
        'tech_id',
        'jobpost_id',
        'status_agreed',
    ];


    public function proposals()
    {
        return $this->hasMany(Proposal::class , 'submission_id');
    }

    public function technician() // users id
    {
        return $this->belongsTo(User::class, 'tech_id');
    }

    public function jobpost()
    {
        return $this->belongsTo(JobPost::class, 'jobpost_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class , 'submission_id'); // الي عمل review بزبطها
    }
}
