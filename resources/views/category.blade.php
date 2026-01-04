@extends('layouts.main')

@section('title', ucfirst($category) . ' Products')

@section('content')
    <h2>{{ ucfirst($category) }} Products</h2>
    
    <div class="product-list">
        @forelse ($products as $product)
            <div class="product-item">
                <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}">
                <p>{{ $product->name }}</p>
                <p>Price: ${{ $product->price }}</p>
                <!-- Link to individual product page -->
                <a href="{{ route('product.show', $product->id) }}"><button>View Details</button></a>
            </div>
        @empty
            <p>No products available in this category.</p>
        @endforelse
    </div>
@endsection