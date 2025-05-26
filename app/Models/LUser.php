<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LUser extends Model
{
    protected $table = 'lianusers';
    protected $primaryKey = 'luser_id';

    protected $fillable = ['name', 'email', 'password'];

    public function jobposts()
    {
        return $this->hasMany(JobPost::class, 'user_id','luser_id');
    }
}
