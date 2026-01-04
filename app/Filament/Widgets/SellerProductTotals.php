<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Seller;
use App\Models\Product;

class SellerProductTotals extends Widget
{
    protected static string $view = 'filament.widgets.seller-product-totals';

    public $sellerTotals = [];
    public $overallTotal = 0;

    public function mount(): void
    {
        $this->sellerTotals = Seller::withCount('products')->get()->map(function ($seller) {
            return [
                'name' => $seller->name,
                'product_count' => $seller->products_count,
            ];
        })->toArray();

        $this->overallTotal = Product::count();
    }
}
