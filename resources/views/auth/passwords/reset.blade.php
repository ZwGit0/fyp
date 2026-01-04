@extends('layouts.main')

@section('title', 'WiseShopperAI - Reset Password')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
    <div class="login-container">
        <h2>Reset Password</h2>
        <p>Enter your new password.</p>

        @if (session('success'))
            <script>
                alert("{{ session('success') }}");
                window.location.href = "{{ route('login') }}";
            </script>
        @endif

        @if ($errors->any())
            <script>
                let errorMessage = "{{ $errors->first() }}";
                if (errorMessage) {
                    alert(errorMessage);
                }
            </script>
        @endif

        <form id="resetPasswordForm" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <input type="password" id="password" name="password" placeholder="New Password">
            <div class="error" id="passwordError"></div>

            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
            <div class="error" id="passwordConfirmationError"></div>

            <button type="submit" id="resetBtn">Reset Password</button>
        </form>
        <div class="back-to-login">
            <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/reset-password.js') }}"></script>
@endsection