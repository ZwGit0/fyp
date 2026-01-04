<div class="filament-widget bg-white p-6 rounded-lg shadow-md border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Total Website Order History</h2>
    <br>
    <div class="space-y-4">
        <div>
            <label for="user-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select User:</label>
            <select wire:model="selectedUser" id="user-select" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                <option value="">-- Select User --</option>
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                @endforeach
            </select>
        </div>

        @if($selectedUser)
            @forelse($orders as $order)
                <div class="border border-gray-200 p-5 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Order ID: {{ $order['id'] }}</h3>
                    <div class=" grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700 dark:text-gray-300">
                        <div>
                            <p><span class="font-medium">Name:</span> {{ $order['name'] }}</p>
                            <p><span class="font-medium">Email:</span> {{ $order['email'] }}</p>
                            <p><span class="font-medium">Phone No:</span> {{ $order['phone'] }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Delivery Address:</span> {{ $order['delivery_address'] }}</p>
                            <p><span class="font-medium">Payment Method:</span> {{ ucfirst($order['payment_method']) }}, {{ $order['card_number'] }}</p>
                            <p><span class="font-medium">Status:</span> 
                                @if($order['status'] === 'received')
                                    Received
                                @else
                                    {{ ucfirst($order['status']) }}
                                @endif
                            </p>
                            <p><span class="font-medium">Subtotal:</span> RM {{ number_format($order['subtotal'], 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Items:</h4>
                        <ul class="space-y-1">
                            @foreach($order['items'] as $index => $item)
                                <li class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $index + 1 }}. {{ $item['name'] }} (RM {{ number_format($item['price'], 2) }}, Qty: {{ $item['quantity'] }})
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 dark:text-gray-400">No orders found for this user.</p>
            @endforelse
        @endif
    </div>
</div>