@extends('layouts.main')

@section('title', 'WiseShopperAI - Forgot Password')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
    <div class="login-container">
        <h2>Forgot Password</h2>
        <p>Enter your name and registered email to reset your password.</p>

        @if (session('message'))
            <div class="success">
                {{ session('message') }}
                <div class="reset-link-container">
                    <p>Click the link below to reset your password:</p>
                    <a href="{{ session('reset_link') }}" target="_blank">Click here to reset your password</a>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <script>
                let errorMessage = "{{ $errors->first() }}";
                if (errorMessage) {
                    alert(errorMessage);
                }
            </script>
        @endif

        <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}">
            @csrf
            <input type="text" id="name" name="name" placeholder="Full Name">
            <div class="error" id="nameError"></div>

            <input type="email" id="email" name="email" placeholder="Email">
            <div class="error" id="emailError"></div>

            <button type="submit" id="submitBtn">Send Reset Link</button>
        </form>
        <br>
        <div class="back-to-login">
            <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/forgot-password.js') }}"></script>
@endsection