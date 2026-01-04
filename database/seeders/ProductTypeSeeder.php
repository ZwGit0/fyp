<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductType;
use App\Models\Category;

class ProductTypeSeeder extends Seeder
{
    public function run()
    {
        // Define product types
        $productTypes = [
            'Shirts',
            'Pants',
            'Shoes',
            'Phone',
            'Vacuum',
            'TV',
        ];

        // Insert product types and categories
        foreach ($productTypes as $typeName) {
            $productType = ProductType::create(['name' => $typeName]);

            // Define categories for each product type
            $categories = match ($typeName) {
                'Shirts' => ['Men\'s Shirt', 'Women\'s Shirt', 'Men\'s Sport Shirts', 'Women\'s Sport Shirts'],
                'Pants' => ['Men\'s Pants', 'Women\'s Pants', 'Men\'s Sport Pants', 'Women\'s Sport Pants'],
                'Shoes' => ['Men\'s Shoes', 'Women\'s Shoes', 'Men\'s Sport Shoes', 'Women\'s Sport Shoes'],
                'Phone' => ['Electronics'],
                'Vacuum' => ['Electronics', 'House Appliances'],
                'TV' => ['Electronics', 'House Appliances'],
                default => []
            };

            // Insert categories linked to product type
            foreach ($categories as $categoryName) {
                Category::create([
                    'name' => $categoryName,
                    'product_type_id' => $productType->id
                ]);
            }
        }
    }
}

