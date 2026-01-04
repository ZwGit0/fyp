@extends('layouts.main')

@section('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
@endsection

@section('content')
    <section class="search-cart-container">
        <div class="search-cart {{ Auth::guard('web')->check() ? 'logged-in' : 'not-logged-in' }}">
            <div class="search-bar">
                <form action="{{ route('search.products') }}" method="GET">
                    <input type="text" name="query" placeholder="Search" oninput="fetchSearchResults(this.value)">
                    <span class="material-icons search-icon">search</span>
                </form>
                <div id="search-results" class="search-dropdown"></div>
                <p id="no-results-message" class="no-results-message"></p>
            </div>
            <div class="cart-icon">
                <a href="{{ route('cart.view') }}">
                    <span class="material-icons">shopping_cart</span>
                </a>
            </div>
        </div>
    </section>

    <section class="sort">
        <select id="sortOptions">
            <option value="sort" id="sortOption">Sort</option>
            <option value="low-to-high">Price: Low to High</option>
            <option value="high-to-low">Price: High to Low</option>
            <option value="alphabet-asc">Alphabetical: A-Z</option>
            <option value="alphabet-desc">Alphabetical: Z-A</option>
        </select>
    </section>

    <section class="product-container">
        <aside class="filter-bar">
            <label><strong>Price</strong></label>
            <div class="price-range">
            <!-- Text inputs for min/max prices -->
                <div class="inputs">
                    <span>RM</span>
                    <input type="number" id="minPriceText" value="{{ request('minPrice', 0) }}" placeholder="Min Price" min="0" step="1">
                    <span>-</span>
                    <span>RM</span>
                    <input type="number" id="maxPriceText" value="{{ request('maxPrice', 99999) }}" placeholder="Max Price" min="0" step="1">
                </div>

                <!-- Min Price Slider -->
                <label for="minPrice">Min Price: RM <span id="minPriceLabel">0</span></label>
                <input type="range" id="minPrice" name="minPrice" min="0" max="99999" step="1" value="{{ request('minPrice', 0) }}">
                
                <!-- Max Price Slider -->
                <label for="maxPrice">Max Price: RM <span id="maxPriceLabel">999</span></label>
                <input type="range" id="maxPrice" name="maxPrice" min="0" max="99999" step="1" value="{{ request('maxPrice', 99999) }}">
            </div>
        </aside>

        <section class="products">
            <div class="product-grid">
                @foreach($products as $product)
                <div class="product-item" data-price="{{ $product->price }}">
                    <a href="{{ route('product.details', $product->id) }}">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                        <p>{{ $product->name }} - RM {{ $product->price }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </section>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('js/products.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>
@endsection
