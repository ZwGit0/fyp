@extends('seller.sellerMain')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/sellerLogin.css') }}">
@endsection

@section('content')
<div class="outer-container">
    <div class="login-container">
        <h2>Reset Password</h2>
        <p>Enter your new password.</p>

        @if (session('success'))
            <script>
                alert("{{ session('success') }}");
                window.location.href = "{{ route('seller.sellerLogin') }}";
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

        <form id="resetPasswordForm" method="POST" action="{{ route('seller.password.update') }}">
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
            <a href="{{ route('seller.sellerLogin') }}">Back to Login</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/seller/seller-reset-password.js') }}"></script>
@endsection