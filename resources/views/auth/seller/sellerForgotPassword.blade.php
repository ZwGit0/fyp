@extends('seller.sellerMain')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/sellerLogin.css') }}">
@endsection

@section('content')
<div class="outer-container">
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

        <form id="forgotPasswordForm" method="POST" action="{{ route('seller.password.email') }}">
            @csrf
            <input type="text" id="name" name="name" placeholder="Full Name">
            <div class="error" id="nameError"></div>

            <input type="email" id="email" name="email" placeholder="Email">
            <div class="error" id="emailError"></div>

            <button type="submit" id="submitBtn">Send Reset Link</button>
        </form>
        <div class="back-to-login">
            <a href="{{ route('seller.sellerLogin') }}">Back to Login</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/seller/seller-forgot-password.js') }}"></script>
@endsection