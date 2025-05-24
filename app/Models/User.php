<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Submission; 
use App\Models\Message; 
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens, Notifiable;
    
    protected $table = 'users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_name', 
        'email',
        'phone',
        'password',
        'country',
        'role_id',
        'is_approved',
    ];

  protected $hidden = ['password'];


  public function role(){
      return $this->belongsTo(Role::class, 'role_id');}


  public function jobposts(){
      return $this->hasMany(JobPost::class, 'user_id');}


  public function notifications(){
      return $this->hasMany(Notification::class, 'user_id');}


  public function reports(){
      return $this->hasMany(Report::class, 'user_id');
    }

    public function profile()
    {
    return $this->hasOne(Profile::class, 'user_id');
    }

    public function submissions()
    {
    return $this->hasMany(Submission::class, 'tech_id');
    }

    public function sentMessages()
    {
    return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
    return $this->hasMany(Message::class, 'receiver_id');
    }

}


