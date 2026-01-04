@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/orderDetails.css') }}">
@endsection

@section('content')
    <div class="order-details-container">
        <h2>Order Details</h2>
        <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
        <br>
        <p><strong>Status:</strong> 
            @if($order->status === 'to_ship')
                To Ship
            @elseif($order->status === 'shipped')
                Shipped
            @elseif($order->status === 'received')
                Received
            @endif
        </p>

        <!-- Order Items -->
        <h3>Order Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td><img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px;"></td>
                        <td>{{ $item->product->name }}</td>
                        <td>RM {{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>RM {{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Order Summary -->
        <div class="order-summary">
            <p><strong>Subtotal:</strong> RM {{ number_format($order->subtotal, 2) }}</p>
            <p><strong>Discount (10%):</strong> RM {{ number_format($order->discount, 2) }}</p>
            <p><strong>Subtotal After Discount:</strong> RM {{ number_format($order->subtotal - $order->discount, 2) }}</p>
            <p><strong>Tax (6%):</strong> RM {{ number_format($order->tax, 2) }}</p>
            <p><strong>Total:</strong> RM {{ number_format($order->total, 2) }}</p>
        </div>

        <!-- Shipping Information -->
        <h3>Shipping Information</h3>
        <div class="shipping-info">
            <p><strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
            <p><strong>Phone:</strong> {{ $order->phone }}</p>
            <p><strong>Address:</strong> {{ $order->address }}, {{ $order->city }}, {{ $order->state }}, {{ $order->zipcode }}, {{ $order->country }}</p>
        </div>

        <a href="{{ route('order.orderStatus') }}" class="btn btn-secondary">Back to Order</a>
    </div>
@endsection