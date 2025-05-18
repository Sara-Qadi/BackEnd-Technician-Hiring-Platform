<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable = [
        'price',
        'description_proposal',
        'submission_id',
    ];


    public function submission()
    {
        return $this->belongsTo(Submission::class , 'submission_id');
    }
}
