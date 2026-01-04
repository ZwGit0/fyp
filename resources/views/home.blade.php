@extends('layouts.main')

@section('title', 'Home')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('content')

    @auth
        <div class="chat-link-container">
            <a href="{{ route('cart.view') }}" class="cart-link">
                Shopping Cart
                <span class="material-icons">shopping_cart</span>
            </a>
            <a href="{{ route('chat') }}" class="chat-link">
                Customer Support
                <span class="material-icons ml-1">support_agent</span>
            </a>
        </div>
    @endauth
    
    <section class="categories">
        @foreach($groupedCategories as $groupName => $groupItems)
            <a href="{{ route('category.products', ['groupName' => strtolower(str_replace(' ', '-', $groupName))]) }}">
                <button>{{ $groupName }}</button>
            </a>
        @endforeach
    </section>

    <section class="hero">
        <h2>Enhance your shopping experience with the all-new AI</h2>
        <p>Your very smart AI dropshipping website with product recommendations tailored to each customer uniquely and chatbot integration to aid your problems!</p>

        <div class="search-bar">
            <form action="{{ route('search.products') }}" method="GET">
                <input type="text" name="query" placeholder="Search" oninput="fetchSearchResults(this.value)">
            </form>
            <div id="search-results" class="search-dropdown"></div>
            <p id="no-results-message" class="no-results-message"></p>
        </div>
    </section>

    <section class="products">
        @foreach($productTypes as $productType)
            <div class="product-item">
                <a href="{{ route('product-type.products', $productType->id) }}">
                    <img src="{{ asset($imageMap[$productType->name] ?? 'images/default.png') }}" alt="{{ $productType->name }}">
                    <p>{{ $productType->name }}</p>
                </a>
            </div>
        @endforeach
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('js/search.js') }}"></script>
@endsection