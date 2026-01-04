@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/orderStatus.css') }}">
@endsection

@section('content')
    <div class="order-status-container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <h2>Order Status</h2>

        @if($orders->isEmpty())
            <p>You have no orders to display.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            <td>RM {{ number_format($order->total, 2) }}</td>
                            <td>
                                @if($order->status === 'to_ship')
                                    To Ship
                                @elseif($order->status === 'shipped')
                                    Shipped
                                @elseif($order->status === 'received')
                                    Received
                                @endif
                            </td>
                            <td>
                                @if(Auth::guard('web')->check())
                                    @if($order->status === 'to_ship')
                                        <form action="{{ route('order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                        </form>
                                    @elseif($order->status === 'shipped')
                                        <form action="{{ route('order.receive', $order->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Mark as Received</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('order.show', $order->id) }}" class="btn btn-primary btn-sm">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection