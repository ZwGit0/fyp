@extends('seller.sellerMain')

@section('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/seller/products/list.css') }}">
@endsection

@section('content')
<div class="container">
    <h2>My Products</h2>

    <!-- Product List -->
    <div class="product-list">
        @if ($products->isEmpty()) 
            <p class="text-muted">You currently have no products. Please add some!</p>
        
             <!-- Fixed Buttons -->
             <div class="fixed-buttons-empty">
                <a href="{{ route('seller.products.create') }}" class="btn btn-primary">Add New Product</a>
                <a href="{{ route('seller.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>

        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Product Type</th>
                        <th>Categories</th>
                        <th>Description</th>
                        <th>Attributes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td><img src="{{ asset($product->image) }}"></td>
                            <td>{{ $product->name }}</p></td>
                            <td>RM {{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ optional($product->productType)->name ?? 'N/A' }}</td>
                            <td>
                                <!-- Display Categories -->
                                @if($product->categories->isNotEmpty())
                                    <ul>
                                        @foreach($product->categories as $category)
                                            <li>{{ $category->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No categories</p>
                                @endif
                            </td>
                            <td>{{ \Str::limit($product->description, 50) }}</td>
                            <td>
                                @if($product->attributes && is_array($product->attributes))
                                    <ul>
                                        @foreach($product->attributes as $key => $value)
                                            <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No attributes</p>
                                @endif
                            </td>
                            <div class="action-buttons">
                                <td>
                                    <a href="{{ route('seller.products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                    </form>
                                </td>
                            </div>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Fixed Buttons -->
            <div class="fixed-buttons">
                <a href="{{ route('seller.products.create') }}" class="btn btn-primary">Add New Product</a>
                <a href="{{ route('seller.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        @endif
    </div>
</div>

@endsection
