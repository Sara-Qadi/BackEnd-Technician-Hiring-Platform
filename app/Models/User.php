<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Review;
use App\Models\Jobpost;

class User extends Authenticatable
{
    use Notifiable;
use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'user_id';
      use HasFactory;
    protected $fillable = [
        'user_name', 
        'email',
        'phone',
        'password',
        'country',
        'role_id',
    ];



  public function role(){
      return $this->belongsTo(Role::class, 'role_id');}


  public function jobposts(){
      return $this->hasMany(Jobpost::class, 'user_id');}


  public function notifications(){
      return $this->hasMany(Notification::class, 'user_id');}


  public function reports(){
      return $this->hasMany(Report::class, 'user_id');
    }

    public function profile()
    {
    return $this->hasOne(Profile::class, 'user_id');
    }
    
public function reviewsGiven()
{
    return $this->hasMany(Review::class, 'review_by');
}

public function reviewsReceived()
{
    return $this->hasMany(Review::class, 'review_to');
}
  

}
