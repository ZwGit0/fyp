@extends('seller.sellerMain')

@section('title', 'WiseShopperAI Seller Hub - Terms of Service')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/sellerTerms.css') }}">
@endsection

@section('content')
<div class="terms-container">
    <br>
    <h1>Terms of Service for Sellers</h1>
    <p class="last-updated">Last Updated: April 26, 2025</p>

    <!-- Introduction -->
    <section class="terms-section">
        <h2>1. Introduction</h2>
        <p>
            Welcome to the WiseShopperAI Seller Hub! These Terms of Service ("ToS") govern your use of the WiseShopperAI Seller Hub platform 
            as a seller. By registering as a seller and using our services, you agree to comply with these terms. If you do not agree, please 
            refrain from using the platform.
        </p>
        <p>
            WiseShopperAI Seller Hub is a dropshipping platform that connects sellers with buyers using AI-powered tools, including personalized 
            product recommendations and a smart chatbot for customer support. These terms apply to all sellers listing products on our platform.
        </p>
    </section>

    <!-- Seller Responsibilities -->
    <section class="terms-section">
        <h2>2. Seller Responsibilities</h2>
        <p>As a seller on WiseShopperAI Seller Hub, you agree to:</p>
        <ul>
            <li>Provide accurate and truthful information about your products, including descriptions, pricing, and availability.</li>
            <li>Ensure timely communication with buyers and fulfill orders promptly through your dropshipping suppliers.</li>
            <li>Handle customer inquiries and disputes professionally, leveraging our AI chatbot when appropriate.</li>
            <li>Comply with all applicable laws and regulations in Malaysia and the jurisdictions of your customers.</li>
            <li>Maintain the quality of your products and ensure they meet the expectations set in your listings.</li>
        </ul>
    </section>

    <!-- Platform Usage Rules -->
    <section class="terms-section">
        <h2>3. Platform Usage Rules</h2>
        <p>You agree not to:</p>
        <ul>
            <li>List fraudulent, counterfeit, or prohibited items on the platform.</li>
            <li>Misuse the AI-powered features, such as manipulating product recommendation data or abusing the chatbot system.</li>
            <li>Engage in any activity that disrupts the platform’s operations, including spamming, hacking, or violating user privacy.</li>
            <li>Use the platform for any unlawful or unethical purposes.</li>
        </ul>
        <p>Violation of these rules may result in suspension or termination of your seller account.</p>
    </section>

    <!-- Payment Terms -->
    <section class="terms-section">
        <h2>4. Payment Terms</h2>
        <p>
            As a dropshipping platform, WiseShopperAI Seller Hub facilitates transactions between sellers and buyers. You are responsible for 
            paying your dropshipping suppliers once an order is placed by a customer. WiseShopperAI may charge a platform fee, which will be 
            deducted from your sales proceeds. Details of the fee structure will be provided in your seller dashboard.
        </p>
        <p>
            Payments to sellers are processed after the order is fulfilled and the return period (if applicable) has expired. WiseShopperAI is 
            not responsible for delays or issues caused by third-party payment processors or suppliers.
        </p>
    </section>

    <!-- AI Features Usage -->
    <section class="terms-section">
        <h2>5. AI Features Usage</h2>
        <p>
            WiseShopperAI Seller Hub uses AI to provide personalized product recommendations and a smart chatbot for customer support. As a seller, 
            you acknowledge that:
        </p>
        <ul>
            <li>Customer data used for recommendations (e.g., purchase history, cart activity) is anonymized and processed in compliance with privacy laws.</li>
            <li>The smart chatbot may handle customer inquiries on your behalf, but you are responsible for resolving complex issues escalated by customers.</li>
            <li>You will not attempt to manipulate or exploit the AI systems for unfair advantage (e.g., falsifying data to influence recommendations).</li>
        </ul>
    </section>

    <!-- Liability and Disclaimers -->
    <section class="terms-section">
        <h2>6. Liability and Disclaimers</h2>
        <p>
            WiseShopperAI Seller Hub provides the platform "as is" and does not guarantee the accuracy, reliability, or availability of its services. 
            We are not liable for:
        </p>
        <ul>
            <li>Issues arising from your dropshipping suppliers, such as delayed shipments or defective products.</li>
            <li>Losses or damages caused by your failure to fulfill orders or meet customer expectations.</li>
            <li>Any indirect, incidental, or consequential damages arising from your use of the platform.</li>
        </ul>
        <p>
            Our main features, includes personalised product recommendations and chatbot responses, are provided to enhance your experience but are not guaranteed 
            to be error-free. You assume full responsibility for how you use these tools.
        </p>
    </section>

    <!-- Termination -->
    <section class="terms-section">
        <h2>7. Termination</h2>
        <p>
            WiseShopperAI reserves the right to suspend or terminate your seller account at our discretion, including but not limited to:
        </p>
        <ul>
            <li>Violation of these Terms of Service.</li>
            <li>Fraudulent activity or repeated customer complaints.</li>
            <li>Failure to fulfill orders or meet platform standards.</li>
        </ul>
        <p>
            Upon termination, you will lose access to the Seller Hub, and any outstanding payments will be processed in accordance with our payment terms.
        </p>
    </section>

    <!-- Governing Law -->
    <section class="terms-section">
        <h2>8. Governing Law</h2>
        <p>
            These Terms of Service are governed by the laws of Malaysia. Any disputes arising from your use of the WiseShopperAI Seller Hub will be 
            resolved in the courts of Malaysia.
        </p>
    </section>

    <!-- Contact Information -->
    <section class="terms-section">
        <h2>9. Contact Information</h2>
        <p>
            If you have any questions or concerns about these Terms of Service, please contact us at:
        </p>
        <p>
            Email: <a href="mailto:seller-support@wiseshopperai.com">seller-support@wiseshopperai.com</a><br>
            Phone: +60 3-1234 5678
        </p>
    </section>
</div>
@endsection