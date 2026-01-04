@extends('layouts.main')

@section('styles')  
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}"> 
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
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<form id="order" method="POST" action="{{ route('order.store') }}">
    @csrf
    <div class="cart-container">
         <!-- Left Side: Form for Contact, Delivery Address, Payment Method -->
         <div class="payment-form">
            <h2>Contact Information</h2>
                <!-- Contact Information -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required disabled>
                    <span id="emailError" class="error-message"></span>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}"required>
                    <span id="phoneError" class="error-message"></span>
                </div>

                <!-- Delivery Address -->
                <h3>Delivery Address</h3>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('address.first_name', $address->first_name ?? '') }}"required>
                    <span id="firstNameError" class="error-message"></span>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="{{  old('address.last_name', $address->last_name ?? '') }}"required>
                    <span id="lastNameError" class="error-message"></span>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="{{ old('address.address', $address->address ?? '') }}"required>
                    <span id="addressError" class="error-message"></span>
                </div>
                
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="{{ old('address.city', $address->city ?? '') }}"required>
                    <span id="cityError" class="error-message"></span>
                </div>
                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" id="state" name="state" value="{{ old('address.state', $address->state ?? '') }}"required>
                    <span id="stateError" class="error-message"></span>
                </div>
                <div class="form-group">
                    <label for="zipcode">Zipcode</label>
                    <input type="text" id="zipcode" name="zipcode" value="{{ old('address.zip_code', $address->zip_code ?? '') }}"required>
                    <span id="zipCodeError" class="error-message"></span>
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" value="{{ old('address.country', $address->country ?? '') }}"required>
                    <span id="countryError" class="error-message"></span>
                </div>

                <!-- Payment Method -->
                <h4>Payment Method</h4>
                <div class="payment-method">
                    <label for="cod">
                        <input type="radio" id="cod" name="payment_method" value="cod" checked> Cash on Delivery (COD)
                    </label>
                    <label for="card">
                        <input type="radio" id="card" name="payment_method" value="card"> Credit / Debit Card
                    </label>
                </div>

                <!-- Card Details (Hidden by Default) -->
                <div id="card-details" style="display:none;">
                    <div class="card-details">
                        <label for="card_number">Card Number</label>
                        <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456">
                        <span id="cardNumberError" class="error-message"></span>
                    </div>
                    <div class="card-details">
                        <label for="expiration_date">Expiration Date</label>
                        <input type="text" id="expiration_date" name="expiration_date" placeholder="MM/YY">
                        <span id="expirationDateError" class="error-message"></span>
                    </div>
                    <div class="card-details">
                        <label for="security_code">Security Code</label>
                        <input type="text" id="security_code" name="security_code" placeholder="e.g. 123">
                        <span id="securityCodeError" class="error-message"></span>
                    </div>
                    <div class="card-details">
                        <label for="card_holder_name">Card Holder Name</label>
                        <input type="text" id="card_holder_name" name="card_holder_name" placeholder="Full Name">
                        <span id="cardHolderNameError" class="error-message"></span>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Right Side: Cart Items -->
        <div class="cart-items">
            <table>
                <tr>
                    <th>Product</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
                @foreach($cartItems as $item)
                <tr class= "row" data-id="{{ $item->id }}">
                    <td><img src="{{ asset( $item->product->image) }}"></td>
                    <td>{{ $item->product->name }}</td>
                    <td>RM {{ number_format($item->product->price, 2) }}</td>
                    <td>
                        <input type="text" class="quantity" id="quantity_{{ $item->id }}" name="quantity[{{ $item->id }}]" value="{{ $item->quantity }}">
                    </td>
                    <td class="total" data-price="{{ $item->product->price }}">RM {{ number_format($item->product->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </table>

            <!--Checkout and Order Details -->

            <div class="order-details">
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

                <p class="price-row">
                    <strong class="shipping">Shipping</strong> <span class="shipping"> Free Shipping </span></p>
                </p>

                <hr>

                <p class="price-row">
                    <strong class="total-label">Total :</strong><span class="priceTotal">RM <span class="total-price"></span></p>
                </p>
                
                 <!-- Hidden Inputs for Order Values -->
                <input type="hidden" name="order-value" id="order-value" value="">
                <input type="hidden" name="discount" id="discount" value="">
                <input type="hidden" name="subtotal_after_discount" id="subtotal_after_discount" value="">
                <input type="hidden" name="tax" id="tax" value="">
                <input type="hidden" name="total-price" id="total-price" value="">
                
                <!-- Your form fields here -->
                <button type="submit" class="order-btn">Place Order</button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/payment.js') }}"></script>
@endsection
