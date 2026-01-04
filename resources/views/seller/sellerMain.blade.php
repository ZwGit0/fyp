<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WiseShopperAI Seller Hub</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/seller/sellerMain.css') }}">
    @yield('styles')  
</head>
<body>
<header class="{{ Auth::guard('seller')->check() ? 'logged-in' : 'logged-out' }}">
        <div class="logo">WiseShopperAI Seller Hub</div>
        <nav>
            <a href="{{ route('seller.about') }}">About us</a>
            <a href="{{ route('seller.terms') }}">Terms of Service</a>
            
            @auth('seller')
                <form id="logout-form" action="{{ route('sellerLogout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); resetBotpressChat(); document.getElementById('logout-form').submit();" class="btn-register">Sign Out</a>
            @else
                <!-- If the user is not logged in, show Sign In -->
                <a href="{{ route('seller.sellerRegister') }}" class="btn-register">Create Account</a>
            @endauth
    </header>

    @if(Auth::guard('seller')->check())
        <!-- Sidebar -->
        <nav id="sidebar">
            <button id="sidebarCollapse" class="sidebar-toggle-btn">
                <span class="material-icons">menu</span>
            </button>
            <ul class="list-unstyled components">
                <li class="{{ Route::is('seller.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('seller.dashboard') }}">Dashboard</a>
                </li>
                <li class="{{ Route::is('seller.profile.sellerProfile') ? 'active' : '' }}">
                    <a href="{{ route('seller.profile.sellerProfile') }}">Profile</a>
                </li>
                <li class="{{ Route::is('seller.products.list') ? 'active' : '' }}">
                    <a href="{{ route('seller.products.list') }}">My Products</a>
                </li>
                <li>
                    <a href="{{ route('seller.orders.to-ship') }}">Orders To Be Shipped</a>
                </li>
                <li class="{{ Route::is('seller.chat') ? 'active' : '' }}">
                    <a href="{{ route('seller.chat') }}">Seller Support Chat</a>
                </li>
            </ul>
        </nav>
    @endif

    <main>
        @yield('content') <!-- Content from specific views will be injected here -->
        {{ $slot ?? '' }}
    </main>

    <!-- Footer -->
    <footer id="footer">
        <p>&copy; {{ date('Y') }} WiseShopperAI Seller Hub. All rights reserved.</p>
    </footer>
    <script src="{{ asset('js/seller/sellerMain.js') }}"></script>
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
