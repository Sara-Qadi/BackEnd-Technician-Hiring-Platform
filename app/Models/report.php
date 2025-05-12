<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'report';
    protected $primaryKey = 'ReportId';
    public $timestamps = true;

    protected $fillable = [
        'UserId',
        'JobPostId',
        'reason',
        'ReportType',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'JobPostId');
    }
}