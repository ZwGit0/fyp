<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable
{
    use HasFactory;

    // Specify the custom table name if it's different from the plural of the model name
    protected $table = 'filament_users';  // Laravel will not assume this automatically

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',  // Add the is_admin field to specify admin users
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
