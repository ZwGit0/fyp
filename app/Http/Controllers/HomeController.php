<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ProductType;

class HomeController extends Controller
{
    public function index()
    {
        // Define grouped categories (without checking database)
        $groupedCategories = [
            'Men' => ["Men's Shirt", "Men's Pants", "Men's Shoes"],
            'Women' => ["Women's Shirt", "Women's Pants", "Women's Shoes"],
            'Sports' => ["Men's Sport Shirts", "Men's Sport Pants", "Men's Sport Shoes", "Women's Sport Shirts", "Women's Sport Pants", "Women's Sport Shoes"],
            'Electronics' => ['Electronics'],
            'Home Appliances' => ['Home Appliances'],
        ];

        // No need to fetch categories from DB, just send group names to view
        $productTypes = ProductType::all();

        // Map product types to images
        $imageMap = [
            'Shirts' => 'images/Men shirts/Men black long sleeves.png',
            'Pants' => 'images/Women pants/Women sport pants.png',
            'Shoes' => 'images/Men shoes/Men sneakers shoes.png',
            'Phone' => 'images/Phone/Sun 30.png',
            'Vacuum' => 'images/Vacuum/Vacuum HE0121.png',
            'TV' => 'images/TV/STV50.png',
        ];

        return view('home', compact('groupedCategories', 'productTypes', 'imageMap'));
    }
}
