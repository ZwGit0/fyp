@extends('layouts.main')

@section('styles')  
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}"> 
@endsection

@section('content')
    <h1 class="shopping-bag-header">Shopping Bag</h1>

    <section class="search-cart-container">
        <div class="search-cart">
            <div class="search-bar">
                <form action="{{ route('search.products') }}" method="GET">
                    <input type="text" name="query" placeholder="Search" oninput="fetchSearchResults(this.value)">
                    <span class="material-icons search-icon">search</span>
                </form>
                <div id="search-results" class="search-dropdown"></div>
                <p id="no-results-message" class="no-results-message"></p>
            </div>
        </div>
    </section>

    @if(session('success'))
        <script>
            window.onload = function() {
                alert("{{ session('success') }}");
            }
        </script>
    @endif

    @if($cartItems->isEmpty())
        <p class="empty-cart-message">Your shopping cart is empty.</p>

    @else
        <div class="cart-container">
            <!-- Left Side: Cart Items -->
            <div class="cart-items">
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Stock left</th>
                        <th>Actions</th>
                    </tr>
                    @foreach($cartItems as $item)
                    <tr data-id="{{ $item->id }}" data-reserved-stock="{{ $reservedStocks[$item->product->id] }}">
                        <td><img src="{{ asset( $item->product->image) }}"></td>
                        <td>{{ $item->product->name }}</td>
                        <td>RM {{ number_format($item->product->price, 2) }}</td>
                        <td>
                            <button type="button" class="decrease" data-id="{{ $item->id }}" data-quantity="{{ $item->quantity }}"  data-reserved-stock="{{ $reservedStocks[$item->product->id] }}">-</button>
                            <input type="text" class="quantity" value="{{ $item->quantity }}">
                            <button type="button" class="increase" data-id="{{ $item->id }}" data-stock="{{ $item->product->stock }}"  data-reserved-stock="{{ $reservedStocks[$item->product->id] }}">+</button>
                        </td>
                        <td class="total" data-price="{{ $item->product->price }}">RM {{ number_format($item->product->price * $item->quantity, 2) }}</td>
                        <td>Stock: {{ $item->product->stock }}</td>
                        <td>
                            <button type="button" class="remove-btn" data-id="{{ $item->id }}">Remove</button>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>

            <!-- Right Side: Checkout and Order Details -->
            <div class="checkout-details">
                <h2>Checkout Details</h2>
                
                <hr>

                <p class="price-row">
                    <strong>Order Value:</strong> <span class="price">RM <span class="order-value"></span></p>
                </p>
                <p class="price-row">
                    <strong>Discount (10%):</strong> <span class="price">- RM <span class="discount"></span></p>
                </p>           
                <p class="price-row">
                    <strong>Subtotal After Discount:</strong> <span class="price">RM <span class="subtotal-after-discount"></span></p>
                </p>                   
                <p class="price-row">
                    <strong>Tax (6%):</strong> <span class="price">RM <span class="tax"></span></p>
                </p>

                <hr>

                <p class="price-row">
                    <strong class="total-label">Total :</strong><span class="priceTotal">RM <span class="total-price"></span></p>
                </p>
                
                <a href="{{ route('order.payment') }}" class="checkout-btn">Checkout</a>

                <!-- Payment Methods -->
                <div class="payment-methods">
                    <p class="payment-note"><strong>We accept:</strong></p>
                    <p> 
                        <span>Cash on Delivery</span> | 
                        <span><i class="material-icons">credit_card</i> Card Payment</span>
                    </p>
                </div>

                <div class="other-info">
                    <p><strong>Note:</strong> You will be redirected to the payment page after checkout.</p>
                    <p><strong>30 days free return on all products.</strong></p>
                </div>
            </div>
        </div>
    @endif

    @if($recommendedProducts->isNotEmpty())
    <hr class="recommended-line"> 
        <h2 class="recommended-heading">Based On Your Cart Item Preferences</h2>
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
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>
@endsection
