<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Category;
use App\Models\Seller;
use App\Models\Cart;
use App\Models\OrderItem;

class ProductController extends Controller
{

    public function getProductsByCategory($groupName)
    {
        $categoryNames = [
            'men' => ["Men's Shirt", "Men's Pants", "Men's Shoes"],
            'women' => ["Women's Shirt", "Women's Pants", "Women's Shoes"],
            'sports' => ["Men's Sport Shirts", "Men's Sport Pants", "Men's Sport Shoes", "Women's Sport Shirts", "Women's Sport Pants", "Women's Sport Shoes"],
            'electronics' => ['Electronics'],
            'home Appliances' => ['Home Appliances'],
        ];

        // Get category IDs from DB
        $categories = Category::whereIn('name', $categoryNames[$groupName] ?? [])->pluck('id');

        // Fetch products linked to these categories
        $products = Product::whereHas('categories', function ($query) use ($categories) {
            $query->whereIn('categories.id', $categories);
        })->get();

        return view('products.index', compact('products', 'groupName'));
    }

    public function getProductsByType($productTypeId)
    {
        $productType = ProductType::findOrFail($productTypeId);
        $products = Product::where('product_type_id', $productTypeId)->get();
        return view('products.index', compact('products', 'productType'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'LIKE', "%$query%")->get();

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with('seller', 'categories', 'productType')->findOrFail($id);
        
        // Get recommended products based on cart and order history
        $recommendedProducts = $this->getRecommendedOrderedProducts($id);

        return view('products.show', compact('product', 'recommendedProducts'));
    }

    public function getRecommendedOrderedProducts()
    {
        $userId = auth('web')->user()->id ?? null;
    
        if ($userId) {
            // Fetch products from orders by the user and their frequencies (order count)
            $orderItems = OrderItem::whereHas('order', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->select('product_id', \DB::raw('sum(quantity) as total_order_count'))
                ->groupBy('product_id')
                ->orderByDesc('total_order_count')  // Sort by most frequently ordered products
                ->limit(5)  // Recommend top 5
                ->get();
    
             // Get the product IDs of the most frequently ordered products
            $orderedProductIds = $orderItems->pluck('product_id');

            // Get the categories of these most ordered products
            $categories = Product::whereIn('id', $orderedProductIds)
                ->with('categories')  // Assuming 'categories' is a relationship on Product model
                ->get()
                ->flatMap(function ($product) {
                    return $product->categories->pluck('id');
                })
                ->unique();

            // Fetch products from the same categories (but avoid recommending the same products)
            $recommendedProducts = Product::whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('categories.id', $categories);
            })
            ->where('stock', '>', 0)  // Only recommend in-stock products
            ->whereNotIn('id', $orderedProductIds)  // Exclude already ordered products
            ->get();

        // Randomize the recommended products and return the top 5
        // If the list is large, you can filter based on specific logic, such as:
        // - Giving priority to products from categories with more ordered items
        // - Giving priority to products that are not yet ordered
        $randomizedProducts = $recommendedProducts->shuffle();  // Randomize to ensure variety
        $topRecommendedProducts = $randomizedProducts->take(5);  // Limit to top 5

        return $topRecommendedProducts;
    }

        return [];
    }

}
