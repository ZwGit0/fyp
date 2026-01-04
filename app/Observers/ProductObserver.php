<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Cart;

class ProductObserver
{
    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product)
    {
        Cart::where('product_id', $product->id)->delete();
    }
}