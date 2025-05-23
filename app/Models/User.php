<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use  HasFactory, Notifiable  ;

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
}
