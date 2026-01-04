@extends('seller.sellerMain')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/order/ordersToShip.css') }}">
@endsection

@section('content')
    <div class="orders-to-ship-container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h2>Orders To Ship</h2>

        @if($orders->isEmpty())
            <p>No orders to ship at the moment.</p>
        @else
            @foreach($orders as $order)
                <div class="order-card">
                    <h3>Order #{{ $order->order_number }}</h3>
                    <p><strong>Placed on:</strong> {{ $order->created_at->format('Y-m-d H:i:s') }}</p>
                    <p><strong>Customer:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                    <p><strong>Shipping Address:</strong> {{ $order->address }}, {{ $order->city }}, {{ $order->state }}, {{ $order->zipcode }}, {{ $order->country }}</p>

                    <!-- Order Items -->
                    <br>
                    <h4>Items</h4>
                    <br>
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

                    <!-- Action Button -->
                    @php
                        $sellerId = Auth::guard('seller')->id();
                        $shippedSellers = Cache::get("order:{$order->id}:shipped_sellers", []);
                        $hasShipped = in_array($sellerId, $shippedSellers);
                    @endphp

                    <!-- Action Button -->
                    <form action="{{ route('order.ship', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $hasShipped ? 'btn-success' : 'btn-primary' }}" {{ $hasShipped ? 'disabled' : '' }}>
                            {{ $hasShipped ? 'Shipped' : 'Mark as Shipped' }}
                        </button>
                    </form>
                </div>
            @endforeach
        @endif
    </div>
@endsection