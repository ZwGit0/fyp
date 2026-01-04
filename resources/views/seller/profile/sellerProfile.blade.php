@extends('seller.sellerMain')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/sellerProfile.css') }}">
@endsection

@section('content')
<div class="container">
    <h2>Profile Details</h2>
    <div class="line"></div>
    
    <p><strong>Seller's Name:</strong> {{ Auth::guard('seller')->user()->name }}</p>
    <p><strong>Seller's Email:</strong> {{ Auth::guard('seller')->user()->email }}</p>
    <p><strong>Seller's Phone Number:</strong> {{ Auth::guard('seller')->user()->phone }}</p>
    @if(Auth::guard('seller')->user()->address_full)
        <p><strong>Seller's Address:</strong> {{ Auth::guard('seller')->user()->address_full }}</p>
    @else
        <p>Full Address not available</p>
    @endif

    <a href="{{ route('seller.profile.edit') }}" class="btn btn-primary">Edit Profile</a>

    <a href="{{ route('seller.dashboard') }}" class="btn">Back to Dashboard</a>
</div>
@endsection
