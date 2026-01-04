@extends('seller.sellerMain')

@section('styles')  
    <link rel="stylesheet" href="{{ asset('css/seller/sellerRegister.css') }}"> 
@endsection

@section('content')
    <div class="register-container">
        <h2>Register account</h2>
        <p>Welcome to WiseShoppingAI Seller Hub, please fill out the form below to register a seller account</p>
        
        <!-- Display success message -->
        @if (session('success'))
            <script>
                alert("{{ session('success') }}");
                window.location.href = "{{ route('seller.sellerLogin') }}";
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

        <form id="registerForm" method="POST" action="{{ route('seller.sellerRegister') }}">
            @csrf

            <!-- Name -->
            <input type="text" id="name" name="name" placeholder="Name" value="{{ old('name') }}">
            <div class="error" id="nameError"></div>
            
            <!-- Email -->
            <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
            <div class="error" id="emailError"></div>
            
            <!-- Phone -->
            <input type="text" id="phone" name="phone" placeholder="Phone Number" value="{{ old('phone') }}">
            <div class="error" id="phoneError"></div>

            <!-- Address -->
            <input type="text" id="address" name="address" placeholder="Address" value="{{ old('address') }}">
            <div class="error" id="addressError"></div>

            <!-- City -->
            <input type="text" id="city" name="city" placeholder="City" value="{{ old('city') }}">
            <div class="error" id="cityError"></div>

            <!-- State -->
            <input type="text" id="state" name="state" placeholder="State" value="{{ old('state') }}">
            <div class="error" id="stateError"></div>

            <!-- Zip Code -->
            <input type="text" id="zip_code" name="zip_code" placeholder="Zip Code" value="{{ old('zip_code') }}">
            <div class="error" id="zipCodeError"></div>

            <!-- Country -->
            <input type="text" id="country" name="country" placeholder="Country" value="{{ old('country') }}">
            <div class="error" id="countryError"></div>
            
            <!-- Password -->
            <input type="password" id="password" name="password" placeholder="Password">
            <div class="error" id="passwordError"></div>
            
            <!-- Confirm Password -->
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
            <div class="error" id="confirmPasswordError"></div>
            
            <button type="submit" id="registerBtn">Sign Up</button>
        </form>
        <div class="login-link">
            <p>Already have a seller account? <a href="{{ route('seller.sellerLogin') }}">Log in here</a></p>
        </div>
    </div>
@endsection

@section('scripts')  
    <script src="{{ asset('js/seller/sellerRegister.js') }}"></script> 
@endsection
