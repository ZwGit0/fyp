@extends('layouts.main')

@section('styles')  
    <link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}"> 
@endsection

@section('content')
<div class="edit-container">
    <h2>Edit Profile</h2>

    <!-- Display success message -->
    @if (session('success'))
        <script>
            alert("{{ session('success') }}");
            window.location.href = "{{ route('profile.show') }}";
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
        
    <form id="editForm" action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Profile Details -->

        <input type="text" id="name" name="name" placeholder="Name" value="{{ old('name', $user->name) }}">
        <div class="error" id="nameError"></div>

        <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email', $user->email) }}" readonly>
        <div class="error" id="emailError"></div>

        <input type="text" name="phone" id="phone" placeholder="Phone Number" value="{{ old('phone', $user->phone) }}">
        <div class="error" id="phoneError"></div>

        <hr>

        <!-- Address Details -->
        <h3>Address</h3>

        <input type="text" name="address[first_name]" placeholder="First Name" id="address_first_name" value="{{ old('address.first_name', $address->first_name) }}">
        <div class="error" id="firstNameError"></div>

        <input type="text" name="address[last_name]" placeholder="Last Name" id="address_last_name" value="{{ old('address.last_name', $address->last_name) }}">
        <div class="error" id="lastNameError"></div>

        <input type="text" name="address[address]" placeholder="Address" id="address_address" value="{{ old('address.address', $address->address) }}">
        <div class="error" id="addressError"></div>

        <input type="text" name="address[city]" placeholder="City" id="address_city" value="{{ old('address.city', $address->city) }}">
        <div class="error" id="cityError"></div>

        <input type="text" name="address[state]" placeholder="State" id="address_state" value="{{ old('address.state', $address->state) }}">
        <div class="error" id="stateError"></div>
    
        <input type="text" name="address[zip_code]" placeholder="Zip Code" id="address_zip_code" value="{{ old('address.zip_code', $address->zip_code) }}">
        <div class="error" id="zipCodeError"></div>
    
        <input type="text" name="address[country]" placeholder="Country" id="address_country" value="{{ old('address.country', $address->country) }}">
        <div class="error" id="countryError"></div>

        <button type="submit">Update Profile and Address</button>
    </form>
</div>
@endsection

@section('scripts')  
    <script src="{{ asset('js/profile/edit.js') }}"></script> 
@endsection
