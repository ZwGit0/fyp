@extends('seller.sellerMain')

@section('styles')  
    <link rel="stylesheet" href="{{ asset('css/seller/sellerLogin.css') }}"> 
@endsection

@section('content')
<div class="outer-container">
    <div class="login-container">
        <h2>Seller Login</h2>
        <p>WiseShopperAI: Where sellers and buyers seamlessly connect through the power of AI.</p>

        @if ($errors->any())
            <script>
                let errorMessage = "{{ $errors->first('email') }}";
                if (errorMessage) {
                    alert(errorMessage);
                }
            </script>
        @endif

        <form id = "loginForm" method="POST" action="{{ route('seller.sellerLogin') }}">
            @csrf
            <input type="email" id="email" name="email" placeholder="Email">
            <div class="error" id="emailError"></div>

            <input type="password" id="password" name="password" placeholder="Password">
            <div class="error" id="passwordError"></div>
            
            <button type="submit" onclick="resetBotpressChat();" id="loginBtn">Login</button>
        </form>
        <div class="forgot-password">
            <a href="{{ route('seller.password.request') }}">Forgot your password?</a>
        </div>
        <div class="register">
            <hr>
            <p>Not a seller of WiseShopperAI?</p>
            <a href="{{ route('seller.sellerRegister') }}" class="register-btn">Create your WiseShopperAI Seller Hub account</a>
        </div>
    </div>
</div>   
@endsection

@section('scripts') 
    <script src="{{ asset('js/login.js') }}"></script> 

    <script>
        function resetBotpressChat() {
            // Clear specific Botpress session storage keys
            sessionStorage.removeItem('bp-webchat');
            sessionStorage.removeItem('bp-conversation');
            // Clear all Botpress-related session storage keys for robustness
            Object.keys(sessionStorage).forEach(key => {
                if (key.startsWith('bp-')) {
                    sessionStorage.removeItem(key);
                }
            });
            // Send reset event to Botpress WebChat (if initialized)
            if (window.botpressWebChat) {
                window.botpressWebChat.sendEvent({ type: 'reset' });
                console.log('Botpress reset event sent');
            }
            // Reload the Botpress iframe to ensure reset
            const iframe = document.querySelector('iframe[name="webchat"]');
            if (iframe) {
                iframe.src = iframe.src; // Reload iframe to reset state
                iframe.classList.add('bpReset'); // Ensure bpReset class is applied
                console.log('Botpress iframe reloaded');
            }
            // Log for debugging
            console.log('Botpress session storage cleared');
        }
    </script>
@endsection
