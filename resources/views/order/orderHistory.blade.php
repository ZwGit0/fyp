@extends('layouts.main')

@section('styles')  
    <link rel="stylesheet" href="{{ asset('css/order.css') }}"> 
@endsection

@section('content')
<div class="container">
    <h1 class="title">Your Order History</h1>

    <div class="order-list">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Number</th>
                    <th>Order Date</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zipcode</th>
                    <th>Country</th>                          
                    <th>Payment Method</th>
                    <th>Card Number</th>
                    <th>Card Brand</th>
                    <th>Subtotal</th>
                    <th>Discount</th>
                    <th>Tax</th>
                    <th>Total</th> 
                    <th>Products</th> 
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>{{ $order->first_name }}</td>
                        <td>{{ $order->last_name }}</td> 
                        <td>{{ $order->phone }}</td> 
                        <td>{{ $order->address }}</td>
                        <td>{{ $order->city }}</td>
                        <td>{{ $order->state }}</td>
                        <td>{{ $order->zipcode }}</td>
                        <td>{{ $order->country }}</td>                                                          
                        <td>{{ $order->paymentMethod->payment_method_type }}</td> 
                        <td>
                            <div class="card-info">
                                @if ($order->paymentMethod->payment_method_type == 'card')
                                    <!-- Format card number as xxxx xxxx xxxx 4992 -->
                                    @php
                                        $cardNumber = $order->paymentMethod->card_last4;
                                        $formattedCardNumber = 'xxxx xxxx xxxx ' . $cardNumber;
                                    @endphp
                                    {{ $formattedCardNumber }}
                                @else
                                    <span>N/A</span>
                                @endif
                            </div>
                        </td> <!-- Display formatted card number -->
                        <td>
                            @if ($order->paymentMethod->payment_method_type == 'card')
                                <!-- Display the card brand -->
                                {{ ucfirst($order->paymentMethod->card_brand) }}
                            @else
                                N/A
                            @endif
                        </td> <!-- Display card brand -->
                        <td>${{ number_format($order->subtotal, 2) }}</td>
                        <td>${{ number_format($order->discount, 2) }}</td>
                        <td>${{ number_format($order->tax, 2) }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td>
                            <div class="order-items-container">
                                @if ($order->orderItems->isNotEmpty()) <!-- Check if there are items -->
                                    @foreach ($order->orderItems as $item)
                                        <div class="order-item-details">
                                            @if ($item->product->image)
                                                <img src="{{ asset($item->product->image) }}" alt="Product Image"/>
                                            @else
                                                <p>No image available</p>
                                            @endif

                                            <div>
                                                <p class="product-name">{{ $item->product->name }}</p>
                                                <p><strong>Price:</strong> ${{ number_format($item->price, 2) }}</p>
                                                <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                                                <p><strong>Subtotal:</strong> ${{ number_format($item->quantity * $item->price, 2) }}</p>
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                @else
                                    <p>No products found for this order.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="20" class="no-records">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>    
@endsection
