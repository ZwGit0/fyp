<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductType;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class SellerProductController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = Auth::guard('seller')->id();
    
        $products = Product::where('seller_id', $sellerId)->get();
    
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'products' => $products,
            ]);
        }
    
        return view('seller.products.list', compact('products'));
    }
    
    public function create()
    {
        $productTypes = ProductType::all();
        
        return view('seller.products.create', [
            'productTypes' => $productTypes,
            'categories' => [],
            'productAttributes' => []
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,gif|max:1024',
            'product_type_id' => 'required|exists:product_types,id',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'stock' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'attributes' => 'nullable|array',
        ]);

        // Get seller ID
        $sellerId = Auth::guard('seller')->id();

        // Upload and store the image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $categoryFolder = $this->getFolderName($request->categories[0]);
            $originalFileName = $request->file('image')->getClientOriginalName();
            $imagePath = $request->file('image')->storeAs(
                "images/$categoryFolder", $originalFileName, 'public'
            );
        }

        $attributes = $request->input('attributes', []);

        // Create the product
        $product = Product::create([
            'seller_id' => $sellerId,
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imagePath,
            'product_type_id' => $request->product_type_id,
            'stock' => $request->stock,
            'description' => $request->description,
            'attributes' => $attributes,
        ]);

        // Attach product to selected categories
        $product->categories()->attach($request->categories);

        return redirect()->back()->with('success', 'Products successfully listed!');
    }

    public function edit(Product $product)
    {
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403, 'Unauthorized action.');
        }

        $selectedCategories = $product->categories->pluck('id')->toArray();
        $categories = Category::where('product_type_id', $product->product_type_id)->get();

        $existingAttributes = $product->attributes ?? []; 
    
        $attributes = $this->getAttributesForCategories($selectedCategories, $existingAttributes);
    
        return view('seller.products.edit', [
            'product' => $product,
            'productTypes' => ProductType::all(),
            'categories' => $categories,
            'productAttributes' => $attributes,
            'selectedCategories' => $selectedCategories
        ]);
    }

    public function update(Request $request, Product $product)
    {
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,gif|max:1024',
            'product_type_id' => 'required|exists:product_types,id',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'stock' => 'required|numeric|min:0',
            'description' => 'required|string',
            'attributes' => 'required|array',
        ]);

        if ($request->hasFile('image')) {
            // If the product already has an image, delete the old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image); // Delete old image
            }
            
            // Get the category folder based on the first selected category
            $categoryFolder = $this->getFolderName($request->categories[0]);
            
            // Get the original file name of the uploaded image
            $originalFileName = $request->file('image')->getClientOriginalName();
            
            // Store the image in the appropriate folder
            $imagePath = $request->file('image')->storeAs(
                "images/$categoryFolder", $originalFileName, 'public'
            );
            
            // Update the image path in the product object
            $product->image = $imagePath;
        }

        $attributes = $request->input('attributes', []);
        
        // Update product details
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'product_type_id' => $request->product_type_id,
            'stock' => $request->stock,
            'description' => $request->description,
            'attributes' => $attributes,
        ]);

        // Sync categories
        $product->categories()->sync($request->categories);

        return redirect()->back()->with('success', 'Products updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->categories()->detach();
        $product->delete();

        return redirect()->route('seller.products.list')->with('success', 'Product deleted successfully.');
    }

    // Fetch categories based on selected product type
    public function getCategoriesByProductType(Request $request)
    {
        $productTypeId = $request->input('product_type_id');
        
        // Fetch categories based on the product type
        $categories = Category::where('product_type_id', $productTypeId)->get();

        // Prepare dynamic attributes based on selected categories
        $attributes = $this->getAttributesForCategories($categories);

        return response()->json([
            'categories' => $categories,
            'attributes' => $attributes,
        ]);
    }

    // Helper function to map categories to attributes
    private function getAttributesForCategories($categories, $existingAttributes = [])
    {
        $attributes = [];

        // Convert collection to an array of IDs
        $categoryIds = is_array($categories) ? $categories : $categories->pluck('id')->toArray();

        // Clothing: Shirts & Pants
        if (array_intersect($categoryIds, [1, 2, 3, 4, 5, 6, 7, 8])) {
            $attributes = [
                "Washing recommendation" => '',
                "Fabric type" => '',
                "Size" => '',
            ];
        }
        // Shoes
        elseif (array_intersect($categoryIds, [9, 10, 11, 12])) {
            $attributes = [
                "Shoe material" => '',
                "Sole type" => '',
                "Waterproof" => '',
                "Size (US)" => '',
            ];
        }
        // Phones
        elseif (array_intersect($categoryIds, [13])) {
            $attributes = [
                "Processor" => '',
                "Ram" => '',
                "Storage" => '',
                "Battery" => '',
                "Camera" => '',
                "Screen size" => '',
            ];
        }
        // Vacuum Cleaners
        elseif (array_intersect($categoryIds, [14, 15])) {
            $attributes = [
                "Suction power" => '',
                "Dust capacity" => '',
                "Battery life" => '',
                "Filter type" => '',
            ];
        }
        // TVs
        elseif (array_intersect($categoryIds, [16, 17])) {
            $attributes = [
                "Screen size" => '',
                "Resolution" => '',
                "Panel type" => '',
                "Refresh rate" => '',
                "Smart TV" => '',
                "HDMI ports" => '',
            ];
        }

        return array_merge($attributes, $existingAttributes);
    }

    protected function getFolderName($categoryId): string
    {
        $folderMap = [
            1 => 'Men shirts',
            2 => 'Women shirts',
            3 => 'Men shirts',
            4 => 'Women shirts',

            5 => 'Men pants',
            6 => 'Women pants',
            7 => 'Men pants',
            8 => 'Women pants',

            9 => 'Men shoes',
            10 => 'Women shoes',
            11 => 'Men shoes',
            12 => 'Women shoes',

            13 => 'Phone',

            14 => 'Vacuum',
            15 => 'Vacuum',

            16 => 'TV',
            17 => 'TV',
        ];
        return $folderMap[$categoryId] ?? 'other';
    }

    // Show all orders to ship for the seller's products
    public function showOrdersToShip()
    {
        $sellerId = Auth::guard('seller')->id();

        // Fetch products belonging to the seller
        $sellerProductIds = Product::where('seller_id', $sellerId)->withTrashed()->pluck('id');

        // Fetch orders that contain the seller's products and are in 'to_ship' status
        $orders = Order::where('status', 'to_ship')
            ->whereHas('orderItems', function ($query) use ($sellerProductIds) {
                $query->whereIn('product_id', $sellerProductIds);
            })
            ->with(['orderItems' => function ($query) use ($sellerProductIds) {
                $query->whereIn('product_id', $sellerProductIds)
                ->with(['product' => function ($query) {
                    $query->withTrashed(); // Include soft-deleted products
                }]);
            }])
            ->get();

        return view('seller.orders.to-ship', compact('orders'));
    }

    // Seller marks the order as shipped
    public function markAsShipped(Request $request, $id)
    {
        $order = Order::with(['orderItems.product' => function ($query) {
            $query->withTrashed(); // Include soft-deleted products
        }])->findOrFail($id);
        $seller = Auth::guard('seller')->user();

        // Check if the seller owns any items in the order
        $sellerItems = $order->orderItems->filter(function ($item) use ($seller) {
            return $item->product->seller_id === $seller->id;
        });

        if ($sellerItems->isEmpty()) {
            Session::flash('error', 'You have no items in this order to ship.');
            return redirect()->route('seller.orders.to-ship');
        }

        // Log that this seller has shipped their items
        $cacheKey = "order:{$order->id}:shipped_sellers";
        $shippedSellers = Cache::get($cacheKey, []);
        if (!in_array($seller->id, $shippedSellers)) {
            $shippedSellers[] = $seller->id;
            Cache::put($cacheKey, $shippedSellers, now()->addDays(30)); // Store for 30 days
        }

        // Get all sellers involved in the order
        $allSellers = $order->orderItems->map(fn($item) => $item->product->seller_id)->unique()->values()->toArray();

        // If all sellers have shipped, update the order status to 'shipped'
        if (count(array_intersect($allSellers, $shippedSellers)) === count($allSellers)) {
            $order->update(['status' => 'shipped']);
            Cache::forget($cacheKey); // Clear cache once order is fully shipped
        }

        Session::flash('success', 'Your items in the order have been marked as shipped!');
        return redirect()->route('seller.orders.to-ship');
    }
}