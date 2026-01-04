@extends('seller.sellerMain')

@section('styles')  
    <link rel="stylesheet" href="{{ asset('css/seller/sellerRegister.css') }}"> 
@endsection

@section('content')
<div class="register-container">
    <h2>Edit Profile</h2>

     <!-- Display success message -->
     @if (session('success'))
        <script>
            alert("{{ session('success') }}");
            window.location.href = "{{ route('seller.profile.sellerProfile') }}";
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

    <form  id="registerForm" action="{{ route('seller.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

    <!-- Name -->
    <input type="text" id="name" name="name" placeholder="Name" value="{{ old('name', $seller->name) }}">
    <div class="error" id="nameError"></div>

    <!-- Email -->
    <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email', $seller->email) }}" readonly>
    <div class="error" id="emailError"></div>

    <!-- Phone -->
    <input type="text" id="phone" name="phone" placeholder="Phone Number" value="{{ old('phone', $seller->phone) }}">
    <div class="error" id="phoneError"></div>

    <!-- Address -->
    <input type="text" id="address" name="address[address]" placeholder="Address" value="{{ old('address.address', $seller->address) }}">
    <div class="error" id="addressError"></div>

    <!-- City -->
    <input type="text" id="city" name="address[city]" placeholder="City" value="{{ old('address.city', $seller->city) }}">
    <div class="error" id="cityError"></div>

    <!-- State -->
    <input type="text" id="state" name="address[state]" placeholder="State" value="{{ old('address.state', $seller->state) }}">
    <div class="error" id="stateError"></div>

    <!-- Zip Code -->
    <input type="text" id="zip_code" name="address[zip_code]" placeholder="Zip Code" value="{{ old('address.zip_code', $seller->zip_code) }}">
    <div class="error" id="zipCodeError"></div>

    <!-- Country -->
    <input type="text" id="country" name="address[country]" placeholder="Country" value="{{ old('address.country', $seller->country) }}">
    <div class="error" id="countryError"></div>

    <!-- Password -->
    <input type="password" id="password" name="password" placeholder="Password">
    <div class="error" id="passwordError"></div>

    <!-- Confirm Password -->
    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
    <div class="error" id="confirmPasswordError"></div>

    <button type="submit" class="registerBtn">Save Changes</button>
    </form>
</div>
@endsection

@section('scripts')  
    <script src="{{ asset('js/seller/sellerRegister.js') }}"></script> 
@endsection

