@extends('layouts.main')

@section('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/productshow.css') }}">
@endsection

@section('content')

    @if(session('success'))
        <script>
            window.onload = function() {
                alert("{{ session('success') }}");
            }
        </script>
    @endif

    @if ($errors->any())
        <script>
            window.addEventListener('load', function() {
                let errorMessage = "";
                @foreach ($errors->all() as $error)
                    errorMessage += "{{ addslashes($error) }}\n";
                @endforeach
                if (errorMessage) {
                    alert(errorMessage.trim()); 
                }
            });
        </script>
    @endif
    
    <section>
        <div class="search-cart-container">
            <div class="search-cart {{ Auth::guard('web')->check() ? 'logged-in' : 'not-logged-in' }}">
                <div class="search-bar {{ Auth::guard('web')->check() ? 'logged-in' : 'not-logged-in' }}">
                    <form action="{{ route('search.products') }}" method="GET">
                        <input type="text" name="query" placeholder="Search" oninput="fetchSearchResults(this.value)">
                        <span class="material-icons search-icon">search</span>
                    </form>
                    <div id="search-results" class="search-dropdown  {{ Auth::guard('web')->check() ? 'logged-in' : 'not-logged-in' }}"></div>
                    <p id="no-results-message" class="no-results-message"></p>
                </div>
                <div class="cart-icon">
                    <a href="{{ route('cart.view') }}" id="cart-link" class="needs-login {{ Auth::guard('web')->check() ? '' : 'not-logged-in' }}">
                        <span class="material-icons">shopping_cart</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="product-container">
        <!-- Left Section: Product Images -->
        <div class="product-images">
            <div class="main-image">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
            </div>
        </div>

        <!-- Right Section: Product Details -->
        <div class="product-info">
            <h1 class="product-title">{{ $product->name }}</h1>
            <p class="product-price">RM {{ $product->price }}</p>
            <p class="product-stock" style="color: red;"><strong>{{ $product->stock }} in stock</strong></p>
            
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="needs-login {{ auth()->check() ? '' : 'not-logged-in' }}">
                @csrf
                <button type="submit" class="add-to-cart" data-product-id="{{ $product->id }}">Add to Cart <span class="material-icons">shopping_cart</span></button>
            </form>

            <div class="line-separator"></div>

            <!-- Expandable Seller Info Section -->
            <div class="expandable-section">
                <button class="accordion">
                    <span class="accordion-text">Seller Information</span>
                    <span class="material-icons accordion-icon">expand_more</span>
                </button>
                <div class="panel">
                    @if($product->seller)
                    <p><strong>Seller's Name:</strong> {{ $product->seller->name }}</p>
                    <p><strong>Seller's Email:</strong> {{ $product->seller->email }}</p>
                    <p><strong>Seller's Phone:</strong> {{ $product->seller->phone }}</p>
                    <p><strong>Seller's Address:</strong> {{ $product->seller->address }}</p>
                    @else
                        <p><strong>Seller Information:</strong> Information provided by Admin.</p>
                    @endif
                </div>
            </div>

            <!-- Expandable Sections -->
            <div class="expandable-section">
                <button class="accordion">
                    <span class="accordion-text">Description</span>
                    <span class="material-icons accordion-icon">expand_more</span>
                </button>
                <div class="panel">{{ $product->description }}</div>
            </div>
            
            <div class="expandable-section">
                <button class="accordion">
                    <span class="accordion-text">Details</span>
                    <span class="material-icons accordion-icon">expand_more</span>
                </button>
                <div class="panel">
                    <ul>
                        @php
                            $attributes = is_string($product->attributes) ? json_decode($product->attributes, true) : $product->attributes;
                        @endphp
                        @if(is_array($attributes) && !empty($attributes))
                            @foreach($attributes as $key => $value)
                                <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                            @endforeach
                        @else
                            <li>No details available</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Show Recommended Products ONLY for Logged-In Users using 'web' guard -->
    @if(auth('web')->check() && $recommendedProducts->isNotEmpty())  
    <hr class="recommended-line"> 
        <h2 class="recommended-heading">Based On Your Order Preferences</h2>
            <div class="recommended-products">
                <div class="recommended-products-grid">
                    @foreach($recommendedProducts as $recommendedProduct)
                        <div class="recommended-product-card">
                            <a href="{{ route('product.details', $recommendedProduct->id) }}">
                                <img src="{{ asset($recommendedProduct->image) }}" alt="{{ $recommendedProduct->name }}">
                                <h3>{{ $recommendedProduct->name }}</h3>
                                <p class="product-price">RM {{ $recommendedProduct->price }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('js/productshow.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>
@endsection
