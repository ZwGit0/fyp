<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request, $productId)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Please log in to add items to the cart.');
        }

        $user = Auth::user();
        $product = Product::find($productId);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $reservedStock = Cart::where('product_id', $product->id)
                    ->where('user_id', '!=', $user->id)
                    ->sum('quantity');
                    
        if ($product->stock <= $reservedStock) {
            return redirect()->back()->with('success', 'Sorry, this item is currently out of stock.');
        }

        // Check if the product is already in the cart
        $cartItem = Cart::where('user_id', $user->id)->where('product_id', $product->id)->first();

        if ($cartItem) {
            if (($cartItem->quantity + 1) <= $product->stock - $reservedStock) {
                $cartItem->increment('quantity');
            } else {
                return redirect()->back()->with('success', 'Due to another customer reservation. Item is currently out of stock.');
            }
        } else {
            // Otherwise, create a new cart entry
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        $subtotal = 0;

        // Recalculate cart totals
        foreach ($cartItems as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }
        
        $discount = $subtotal * 0.1; // 10% discount
        $subtotalAfterDiscount = $subtotal - $discount;
        $taxRate = 0.06; 
        $tax = $subtotalAfterDiscount * $taxRate; // 6% tax
        $total = $subtotalAfterDiscount + $tax;

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function viewCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must log in to view your cart.');
        }

        $cartItems = Cart::where('user_id', Auth::id())
        ->with(['product' => function ($query) {
            $query->whereNull('deleted_at'); // Only include non-deleted products
        }])
        ->get();

        $reservedStocks = [];
        foreach ($cartItems as $item) {
            $reservedStocks[$item->product->id] = Cart::where('product_id', $item->product->id)->sum('quantity');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $discount = $subtotal * 0.10; 
        $subtotalAfterDiscount = $subtotal - $discount;
        $taxRate = 0.06; 
        $tax = $subtotalAfterDiscount * $taxRate;
        $total = $subtotalAfterDiscount + $tax;
    
        $recommendedProducts = $this->getRecommendedCartProducts();

        return view('cart.index', compact('cartItems', 'reservedStocks', 'subtotal', 'discount', 'subtotalAfterDiscount', 'tax', 'total', 'recommendedProducts'));
    }

    public function updateCart(Request $request, $cartId)
    {
        $cartItem = Cart::where('id', $cartId)->where('user_id', Auth::id())->firstOrFail();

        $product = $cartItem->product;

        // Get the current reserved stock (sum of quantities in all carts for the same product)
        $reservedStock = Cart::where('product_id', $product->id)->sum('quantity');

        // Ensure that the new quantity does not exceed the available stock
        $availableStock = $product->stock - $reservedStock + $cartItem->quantity; // Include current cart item quantity

        if ($request->quantity > $availableStock) {
            return response()->json(['error' => 'Stock limit reached. Cannot update cart with this quantity.'], 400);
        }

        // Update the cart item quantity
        $cartItem->update(['quantity' => $request->quantity]);

        // Return success response
        return response()->json(['success' => 'Cart updated.']);
    }

    public function removeFromCart($cartId)
    {
        $cartItem = Cart::where('id', $cartId)->where('user_id', Auth::id())->firstOrFail();
        $cartItem->delete();

        // Return success response
        return response()->json(['success' => 'Item will be removed from cart.']);
    }

    public function checkout()
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'You must log in to checkout.');
        }

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        foreach ($cartItems as $item) {
            // Check if there is enough stock to complete the order
            $product = $item->product;
    
            if ($product->stock < $item->quantity) {
                return redirect()->back()->with('error', 'Sorry, not enough stock available for ' . $product->name);
            }
        }

        $user = Auth::user();  // Get the authenticated user
        $address = $user->address;  // Get the user's address

        return view('order.payment', compact('cartItems', 'user', 'address')); // Create checkout view
    }

    public function getRecommendedCartProducts()
    {
        $userId = auth('web')->user()->id ?? null;

        if ($userId) {
            // Fetch products added to cart by the user and their quantities
            $cartItems = Cart::where('user_id', $userId)
                ->select('product_id', \DB::raw('sum(quantity) as total_quantity'))
                ->groupBy('product_id')
                ->orderByDesc('total_quantity')  // Sort by most frequently added products
                ->limit(5)  // Recommend top 5
                ->get();

            // Get the product IDs of the most frequently added products
            $cartProductIds = $cartItems->pluck('product_id');

            // Fetch the categories of these top cart products
            $categories = Product::whereIn('id', $cartProductIds)
                ->with('categories')  // Assuming the 'categories' relation exists on Product model
                ->get()
                ->flatMap(function ($product) {
                    return $product->categories->pluck('id');
                })
                ->unique();

            // Fetch products from the same categories but exclude the products already in the cart
            $recommendedProducts = Product::whereHas('categories', function ($query) use ($categories) {
                    $query->whereIn('categories.id', $categories);
                })
                ->where('stock', '>', 0)  // Only recommend in-stock products
                ->whereNotIn('id', $cartProductIds)  // Exclude products already in the cart
                ->get();

            // Randomize the recommended products
            $randomizedProducts = $recommendedProducts->shuffle();  // Shuffle to get random order

            // Limit to top 5 products (after randomization)
            $topRecommendedProducts = $randomizedProducts->take(5);  // Get the top 5 products

            return $topRecommendedProducts;
        }

        return [];
    }

}
