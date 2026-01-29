<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'reason',
        'description',
        'status',
        'reviewed_by',
        'reviewed_at'
    ];

    // اللي بلّغ
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    // اللي راجع البلاغ (أدمن)
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // العنصر المبلّغ عنه (User / Job / Review ...)
    public function reportable()
    {
        return $this->morphTo();
    }
}
