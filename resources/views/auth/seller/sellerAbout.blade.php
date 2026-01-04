@extends('seller.sellerMain')

@section('title', 'WiseShopperAI - About Us')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endsection

@section('content')
<div class="about-maincontainer">
    <div class="about-container">
        <!-- Hero Section -->
        <br>
        <br>
        <section class="hero-section">
            <h1>About WiseShopperAI</h1>
            <p class="hero-tagline">Empowering smarter shopping with the power of AI.</p>
        </section>

        <!-- Introduction Section -->
        <section class="intro-section">
            <h2>Who We Are</h2>
            <p>
                At WiseShopperAI, we’re revolutionizing the way people shop online by harnessing the power of artificial intelligence. 
                Our mission is to connect buyers and sellers seamlessly, ensuring a smarter, more personalized shopping experience for everyone.
            </p>
            <p>
                Founded in 2025, WiseShopperAI is built on the belief that technology can make shopping more efficient, enjoyable, and accessible. 
                Whether you’re a customer looking for the best deals or a seller aiming to reach the right audience, we’ve got you covered.
            </p>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <h2>What We Offer</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <span class="material-icons feature-icon">smart_toy</span>
                    <h3>Personalised Product Recommendations</h3>
                    <p>Get personalized product suggestions tailored to your frequently added to cart behaviour and shopping history.</p>
                </div>
                <div class="feature-card">
                    <span class="material-icons feature-icon">storefront</span>
                    <h3>Seller Hub</h3>
                    <p>Our Seller Hub empowers sellers with tools to manage products, analytics to track sales, and connect with buyers effortlessly.</p>
                </div>
                <div class="feature-card">
                    <span class="material-icons feature-icon">security</span>
                    <h3>AI Chatbot</h3>
                    <p>Our integrated smart chatbot provides 24/7 customer support, automating responses to common queries and guiding users through the platform, 
                    reducing the need for human customer service staff.</p>
                </div>
                <div class="feature-card">
                    <span class="material-icons feature-icon">support_agent</span>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated support team is here to assist you anytime, ensuring a smooth shopping experience.</p>
                </div>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section class="cta-section">
            <h2>Join the WiseShopperAI Community</h2>
            <p>Ready to experience smarter shopping? Join thousands of users who trust WiseShopperAI to make their shopping journey seamless and enjoyable.</p>
            <div class="cta-buttons">
                <!-- If authenticated as a seller -->
                @if(auth('seller')->check())
                    <a href="{{ route('seller.dashboard') }}" class="cta-btn primary">Explore Seller Hub</a>
                @else
                    <!-- For seller guests -->
                    <a href="{{ route('seller.sellerRegister') }}" class="cta-btn primary">Join as Seller</a>
                    <a href="{{ route('seller.sellerLogin') }}" class="cta-btn secondary">Seller Login</a>
                @endauth
            </div>
        </section>
    </div>
</div>
@endsection