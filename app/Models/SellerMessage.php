<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id', 'sender_type', 'receiver_id', 'receiver_type', 'message'];

    public function sender()
    {
        return $this->morphTo();  // This will dynamically associate either Seller or Admin
    }

    public function receiver()
    {
        return $this->morphTo();  // This will dynamically associate either Seller or Admin
    }
}
