<div class="filament-widget bg-white p-6 rounded-lg shadow-md border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Seller Product Totals</h2>
    <div class="space-y-3">
        @foreach($sellerTotals as $seller)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-md hover:bg-gray-100 transition dark:bg-gray-700 dark:hover:bg-gray-600">
                <span class="text-gray-700 dark:text-gray-300">{{ $seller['name'] }}</span>
                <span class="text-gray-900 font-medium dark:text-gray-100">Total products: {{ $seller['product_count'] }}</span>
            </div>
        @endforeach
        <hr class="my-4 border-gray-200 dark:border-gray-600">
        <div class="flex justify-between items-center">
            <span class="text-gray-800 font-semibold dark:text-gray-200">Total Overall Products by All Sellers:</span>
            <span class="text-primary-600 font-bold text-lg dark:text-primary-400">{{ $overallTotal }}</span>
        </div>
    </div>
</div>