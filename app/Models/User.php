<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Submission;
use App\Models\Message;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Notification;





class User extends Authenticatable
{


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
        'is_approved',
    ];

  protected $hidden = ['password'];


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

    // omar     
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id', 'user_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }


    public function Proposal()
    {
        return $this->hasMany(Proposal::class, 'tech_id', 'user_id');
    }



public function sendPasswordResetNotification($token)
{
    $url = "http://localhost:4200/reset-password?token=$token&email=" . urlencode($this->email);

    $this->notify(new class($url) extends Notification {
        public $url;
        public function __construct($url) { $this->url = $url; }

        public function via($notifiable) { return ['mail']; }

        public function toMail($notifiable)
        {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Reset Your Password')
                ->line('Click the button below to reset your password:')
                ->action('Reset Password', $this->url)
                ->line('If you did not request a password reset, no further action is required.');
        }
    });
}

}


