<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_method_type',
        'card_last4',
        'card_expiry',
        'card_holder_name',
        'card_brand',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
