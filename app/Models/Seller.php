<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address_full', 'address', 'city', 'state', 'zip_code', 'country'    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
