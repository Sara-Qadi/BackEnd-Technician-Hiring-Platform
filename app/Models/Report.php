<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;
    protected $table = 'report';
    protected $primaryKey = 'report_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'reported_user_id',
        'jobpost_id',
        'reason',
        'report_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'jobpost_id');
    }
}
