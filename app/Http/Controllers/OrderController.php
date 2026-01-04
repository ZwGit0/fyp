<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderItems.product')  
                    ->where('user_id', auth()->id()) 
                    ->where('status', 'received')
                    ->orderBy('id', 'asc')
                    ->get();
        
        return view('order.orderHistory', compact('orders'));
    }


    public function store(Request $request)
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }
    
        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $discount = $subtotal * 0.1; // 10% discount
        $subtotalAfterDiscount = $subtotal - $discount;
        $tax = $subtotalAfterDiscount * 0.06; // 6% tax
        $total = $subtotalAfterDiscount + $tax;

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'order-value' => 'required|numeric',
            'discount' => 'required|numeric',
            'subtotal_after_discount' => 'required|numeric',
            'tax' => 'required|numeric',
            'total-price' => 'required|numeric',
            'phone' => 'required',
            'country' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
            'payment_method' => 'required',

            // Only validate card details if payment_method is 'card'
            'card_number' => request('payment_method') === 'card' ? 'required' : 'nullable',
            'expiration_date' => request('payment_method') === 'card' ? 'required' : 'nullable',
            'security_code' => request('payment_method') === 'card' ? 'required' : 'nullable',
            'card_holder_name' => request('payment_method') === 'card' ? 'required' : 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $paymentMethod = null;

        // Handle Payment Method
        if ($request->payment_method === 'card') {
            // Store payment details for card
            $paymentMethod = PaymentMethod::create([
                'user_id' => Auth::id(),
                'payment_method_type' => 'card',
                'card_last4' => substr($request->card_number, -4),  // Store only last 4 digits
                'card_expiry' => $request->expiration_date,
                'card_holder_name' => $request->card_holder_name,
                'card_brand' => $this->getCardBrand($request->card_number), // Optionally, you can use a library to get the card brand
            ]);
        } elseif ($request->payment_method === 'cod') {
            // Store payment method for COD
            $paymentMethod = PaymentMethod::create([
                'user_id' => Auth::id(),
                'payment_method_type' => 'cod',
            ]);
        }

        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => uniqid('ORD-'),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'phone' => $request->phone,
            'country' => $request->country,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zipcode' => $request->zipcode,
            'payment_method_id' => $paymentMethod->id,
            'status' => 'to_ship', // Initial status
        ]);

        // Now create OrderItems for the cart items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id, // Associate the order with the OrderItem
                'product_id' => $item->product->id,  // Link the product
                'quantity' => $item->quantity,  // Store the quantity
                'price' => $item->product->price,  // Store the price
            ]);

            $item->product->decrement('stock', $item->quantity);
        }
    
        Cart::where('user_id', Auth::id())->delete();

        // Redirect to order history page with success message
        Session::flash('success', 'Your order has been placed successfully!');
        return redirect()->route('order.orderStatus', $order->id);
    }

    // Show all order statuses for the authenticated user
    public function showAllStatuses()
    {
        $orders = Order::with('orderItems.product')
                    ->where('user_id', auth()->id())
                    ->get();

        return view('order.orderStatus', compact('orders'));
    }

    // User cancels the order
    public function cancel(Request $request, $id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        // Ensure the user owns the order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow cancellation if status is 'to_ship'
        if ($order->status !== 'to_ship') {
            Session::flash('error', 'Order cannot be canceled after it has been shipped.');
            return redirect()->back();
        }

        // Restore stock for each product in the order
        foreach ($order->orderItems as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        // Delete associated payment method
        if ($order->paymentMethod) {
            $order->paymentMethod->delete();
        }

        // Delete the order and its items
        $order->orderItems()->delete(); // Delete associated order items
        $order->delete(); // Delete the order

        Session::flash('success', 'Order has been cancelled successfully!');
        return redirect()->route('cart.view'); // Redirect to cart or homepage
    }

    // User marks the order as received
    public function markAsReceived(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Ensure the user owns the order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($order->status === 'shipped') {
            $order->update(['status' => 'received']);
            Session::flash('success', 'Order marked as received successfully!');
        }

        return redirect()->route('order.orderHistory');
    }

    public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        // Ensure the user owns the order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('order.orderDetails', compact('order'));
    }

    // Optional helper to determine card brand
    private function getCardBrand($cardNumber)
    {
        // Use a card brand detection library, or use simple checks for popular card types
        $brands = [
            'visa' => '/^4/',
            'mastercard' => '/^5[1-5]/',
            'amex' => '/^3[47]/',
        ];

        foreach ($brands as $brand => $pattern) {
            if (preg_match($pattern, $cardNumber)) {
                return $brand;
            }
        }

        return 'unknown'; // Default if no match found
    }

    public function orderHistory()
    {
        $orders = Order::with('orderItems.product')
                    ->where('user_id', auth()->id())
                    ->where('status', 'received') // Only include received orders
                    ->orderBy('id', 'asc')
                    ->get();
        return view('order.orderHistory', compact('orders'));
    }
}
