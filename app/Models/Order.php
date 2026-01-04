<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_number', 'subtotal', 'discount', 'tax', 'total', 'phone', 'country', 'first_name', 'last_name', 'address', 'city', 'state', 'zipcode', 'payment_method_id', 'status'];

    // Relationship with the User model (an order belongs to a user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with the Address model (an order belongs to an address)
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    // Relationship with the PaymentMethod model (an order has one payment method)
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    // Optional: Add a method to generate a unique order number
    public static function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
