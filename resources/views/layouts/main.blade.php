<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WiseShopperAI')</title>
    @livewireStyles
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @yield('styles')  <!-- Yield for additional styles -->
</head>
<body>
    <header>
        <div class="logo">
            <h1>
                <a href="{{ route('home') }}">WISESHOPPERAI</a>
            </h1>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="{{ route('about') }}">About us</a></li>
                <li><a href="{{ route('seller.logoutAndRedirect') }}">Seller Hub</a></li>

                @if(!request()->is('login') && !request()->is('register'))
                    <!-- Auth links for authenticated users -->
                    @auth
                        <div class="auth-links">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="{{ route('order.orderStatus') }}" class="order">Order</a>
                                        <a href="{{ route('order.orderHistory') }}">History</a>
                                        <a href="{{ route('profile.show') }}" class="profile-link">Profile</a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        
                                        <a href="#" onclick="event.preventDefault(); resetBotpressChat(); document.getElementById('logout-form').submit();">
                                            Logout <span class="material-icons logout-icon">logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    @endauth
                @endif
            </ul>
        </nav>
    </header>
    
    @if(!request()->is('login') && !request()->is('register') && !request()->is('password/reset*'))
        <!-- Auth links for guests -->
        @guest
            <div class="guest-links">
                <nav>
                    <ul>
                        <li><a href="{{ route('login') }}" onclick="resetBotpressChat();" >Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    </ul>
                </nav>
            </div>
        @endguest
    @endif

    <main>
        @yield('content') <!-- Content from specific views will be injected here -->
        {{ $slot ?? '' }}
    </main>

    <footer>
        <!-- Add footer content if needed -->
    </footer>
    @yield('scripts')
    @livewireScripts

    <script src="https://cdn.botpress.cloud/webchat/v2.3/inject.js"></script>
    <script src="https://files.bpcontent.cloud/2025/04/17/03/20250417034255-A5X7IDAE.js"></script>
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
</body>
</html>