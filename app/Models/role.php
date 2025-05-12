<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'RoleId';
    public $timestamps = false;

    protected $fillable = [
        'Name'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'RoleId');
    }
}