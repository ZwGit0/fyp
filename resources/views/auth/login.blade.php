@extends('layouts.main') 

@section('title', 'WiseShopperAI - Login') 

@section('styles')  
    <link rel="stylesheet" href="{{ asset('css/login.css') }}"> 
@endsection

@section('content')  
    <div class="login-container">
        <h2>Login</h2>
        <p>Welcome back to WiseShopperAI</p>

        @if ($errors->any())
            <script>
                let errorMessage = "{{ $errors->first('email') }}";
                if (errorMessage) {
                    alert(errorMessage);
                }
            </script>
        @endif

        <form id = "loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <input type="email" id="email" name="email" placeholder="Email">
            <div class="error" id="emailError"></div>

            <input type="password" id="password" name="password" placeholder="Password">
            <div class="error" id="passwordError"></div>
            
            <button type="submit" id="loginBtn">Login</button>
        </form>
        <div class="forgot-password">
            <a href="{{ route('password.request') }}">Forgot your password?</a>
        </div>
        <div class="register">
            <hr>
            <p>New to WiseShopperAI?</p>
            <br>
            <a href="{{ route('register') }}" class="register-btn">Create your WiseShopperAI account</a>
        </div>
    </div>
@endsection

@section('scripts') 
    <script src="{{ asset('js/login.js') }}"></script> 
@endsection

