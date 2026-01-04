@extends('layouts.main')  

@section('title', 'WiseShopperAI - Login')  

@section('styles')  
    <link rel="stylesheet" href="{{ asset('css/register.css') }}"> 
@endsection

@section('content')
    <div class="register-container">
        <h2>Register account</h2>
        <p>Welcome to WiseShoppingAI, please fill out the form below to register an account</p>
        
        <!-- Display success message -->
        @if (session('success'))
            <script>
                alert("{{ session('success') }}");
                window.location.href = "{{ route('login') }}";
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

        <form id="registerForm" method="POST" action="{{ route('register') }}">
            @csrf

            <input type="text" id="name" name="name" placeholder="Name">
            <div class="error" id="nameError"></div>
            
            <input type="email" id="email" name="email" placeholder="Email">
            <div class="error" id="emailError"></div>
            
            <input type="password" id="password" name="password" placeholder="Password">
            <div class="error" id="passwordError"></div>
            
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
            <div class="error" id="confirmPasswordError"></div>
            
            <button type="submit" id="registerBtn">Sign Up</button>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="{{ route('login') }}">Log in here</a></p>
        </div>
    </div>
@endsection

@section('scripts')  
    <script src="{{ asset('js/register.js') }}"></script> 
@endsection

