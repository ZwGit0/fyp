@extends('seller.sellerMain')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/welcome.css') }}">
@endsection

@section('content')
<div class="hero fade-in">
    <h1>Welcome to WiseShopperAI Seller Hub!</h1>
    <p>Start selling your products today on your very smart AI-driven dropshipping website! With personalized product recommendations tailored uniquely to each customer and integrated chatbot support to help with any questions, you can reach more customers and grow your business effortlessly.</p>
    <a href="{{ route('seller.sellerLogin') }}" class="btn-start">Start Selling Now</a>
</div>
@endsection
