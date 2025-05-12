<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    protected $table = 'specialities';
    protected $primaryKey = ['specialitiesId','ProfileId']; 
    public $timestamps = false;

    protected $fillable = [
        'specialitiesId',
        'ProfileId',
    ];

    public function userProfiles()
    {
        return $this->belongsToMany(
            UserProfile::class,
            'profile_specialities',  // جدول الربط
            'specialitiesId',        // المفتاح من هذا الموديل
            'ProfileId'              // المفتاح من الموديل الآخر
        );
    }
}
