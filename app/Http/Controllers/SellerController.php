<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Seller;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{
    public function index()
    {
        $seller = Auth::guard('seller')->user();
        $products = $seller->products()->whereNull('deleted_at')->get();
        $productCount = $seller->products()->count();
        
        $allProducts = $seller->products()->withTrashed()->get();

        // 1. Total Revenue Generated
        $totalRevenue = OrderItem::whereIn('product_id', $allProducts->pluck('id'))
        ->sum(\DB::raw('price * quantity'));

        // 2. Top 6 Products Sold
        $topProducts = OrderItem::select('product_id')
            ->whereIn('product_id', $allProducts->pluck('id'))
            ->groupBy('product_id')
            ->selectRaw('SUM(quantity) as units_sold')
            ->orderByDesc('units_sold')
            ->take(6)
            ->with(['product' => function ($query) {
                $query->withTrashed(); // Include soft-deleted products
            }])
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product->name,
                    'units_sold' => $item->units_sold,
                ];
            })
            ->toArray();

        // 3. Top 4 Frequently Added to Cart
        $cartItems = Cart::select('product_id')
            ->whereIn('product_id', $products->pluck('id'))
            ->groupBy('product_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->orderByDesc('total_quantity')
            ->take(4)
            ->with('product')
            ->get();

        $totalCartQuantity = $cartItems->sum('total_quantity');

        $topCartItems = $cartItems->map(function ($item) use ($totalCartQuantity) {
            $percentage = $totalCartQuantity > 0 ? round(($item->total_quantity / $totalCartQuantity) * 100) : 0;
            return [
                'name' => $item->product->name,
                'percentage' => $percentage,
            ];
        })->toArray();

        // 4. Detailed List of All Products Sold
        $productsSold = OrderItem::select('product_id')
            ->whereIn('product_id', $allProducts->pluck('id'))
            ->groupBy('product_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(price * quantity) as total_price')
            ->with(['product' => function ($query) {
                $query->withTrashed(); // Include soft-deleted products
            }])
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product->name,
                    'quantity' => $item->total_quantity,
                    'total_price' => $item->total_price,
                ];
            })
            ->toArray();

        return view('seller.dashboard', compact('products', 'productCount', 'totalRevenue', 'topProducts', 'topCartItems', 'productsSold'));
    }

    public function profile()
    {
        $seller = Auth::guard('seller')->user();
        return view('seller.profile.sellerProfile', compact('seller'));
    }

    // Show Edit Profile Form
    public function editProfile()
    {
        $seller = Auth::guard('seller')->user();
        return view('seller.profile.sellerProfileEdit', compact('seller'));
    }

    // Update Profile
    public function updateProfile(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:sellers,email,' . $seller->id,
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address.address' => 'required|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.state' => 'required|string|max:255',
            'address.zip_code' => 'required|string|max:20',
            'address.country' => 'required|string|max:255',
        ],[
            'email.unique' => 'This email address is already registered.',
        ]);
        
        // Combine address fields into one full address field
        $addressFull = $request->input('address.address') . ', ' . 
                        $request->input('address.city') . ', ' .
                        ($request->input('address.state') ? $request->input('address.state') . ', ' : '') .
                        ($request->input('address.zip_code') ? $request->input('address.zip_code') . ', ' : '') .
                        $request->input('address.country');
    
        // Update the seller's data
        $seller->name = $request->input('name');
        $seller->email = $request->input('email');
        $seller->phone = $request->input('phone');
        $seller->address = $request->input('address.address');
        $seller->city = $request->input('address.city');  
        $seller->state = $request->input('address.state'); 
        $seller->zip_code = $request->input('address.zip_code');  
        $seller->country = $request->input('address.country');
        $seller->address_full = $addressFull;


        if ($request->filled('password')) {
            $seller->password = Hash::make($request->input('password'));
        }

        $seller->save();

        return redirect()->back()->with('success', 'Profile update successfully.');
    }
}
