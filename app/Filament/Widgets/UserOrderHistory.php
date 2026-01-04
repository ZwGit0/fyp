<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\User;
use App\Models\Order;

class UserOrderHistory extends Widget
{
    protected static string $view = 'filament.widgets.user-order-history';

    public $users = [];
    public $selectedUser = null;
    public $orders = [];

    public function mount(): void
    {
        $this->users = User::withTrashed() // Include soft-deleted users
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name . ($user->deleted_at ? ' (Deleted)' : ''), // Indicate deleted users
                ];
            })
            ->toArray();
    }

    public function updatedSelectedUser($value)
    {
        $this->orders = Order::where('user_id', $value)
            ->with(['orderItems.product' => function ($query) {
                $query->withTrashed(); // Explicitly include soft-deleted products
            }, 'user' => function ($query) {
                $query->withTrashed(); // Include soft-deleted users
            }, 'paymentMethod'])
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($order) {
                $items = $order->orderItems->map(function ($item) {
                    return [
                        'name' => $item->product ? $item->product->name : 'Deleted Product',
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                    ];
                })->toArray();

                return [
                    'id' => $order->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->phone,
                    'items' => $items,
                    'status' => $order->status, 
                    'subtotal' => $order->subtotal,
                    'delivery_address' => $order->address . ', ' . $order->city . ', ' . $order->state,
                    'payment_method' => $order->paymentMethod->payment_method_type,
                    'card_number' => $order->paymentMethod->payment_method_type == 'card' ? 'xxxx xxxx xxxx ' . $order->paymentMethod->card_last4 : 'N/A',
                ];
            })->toArray();
    }
}
