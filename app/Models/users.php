<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'UserId';
    public $timestamps = false;

    protected $fillable = [
        'UserName',
        'Email',
        'Phone',
        'Password',
        'Country',
        'RoleId', // You should have this in your users table
    ];


  public function role(){
      return $this->belongsTo(Role::class, 'RoleId');}


  public function jobposts(){
      return $this->hasMany(JobPost::class, 'UserId');}


  public function notifications(){
      return $this->hasMany(Notification::class, 'UserId');}


  public function reports(){
      return $this->hasMany(Report::class, 'UserId');
    }

    public function profile()
    {
    return $this->hasOne(UserProfile::class, 'UserId');
    }
}
