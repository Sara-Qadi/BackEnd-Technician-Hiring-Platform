<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';

    protected $primaryKey = 'NotificationId';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'UserId',
        'ReadStatus',
        'Type',
        'Message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'UserId');
    }
}