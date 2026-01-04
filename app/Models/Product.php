<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 
        'product_type_id',
        'price', 
        'image', 
        'stock', 
        'description', 
        'attributes',
        'seller_id'
    ];

    // You may want to cast the attributes column to an array for easy access
    protected $casts = [
        'attributes' => 'array',
    ];

    public function setAttributesAttribute($value)
    {
        $this->attributes['attributes'] = !empty($value) ? json_encode($value) : json_encode([]);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}