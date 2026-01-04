@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/profile/show.css') }}">
@endsection

@section('content')
    <div class="container">
        <h2>Your Profile</h2>
        <div class="line"></div>

        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Phone:</strong> {{ $user->phone }}</p>
        <p><strong>Address:</strong> 
        @if($user->address && $user->address->address_full)
            {{ $user->address->address_full }}
        @else
            Address not available
        @endif
        </p>

        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>

        <form action="{{ route('profile.delete') }}" method="POST" id="delete-form" style="display: none;">
            @csrf
            @method('DELETE')

            <div class="delete-warning">
                <label for="password">Enter your password to confirm deletion:</label>
                <input type="password" name="password" id="password" required>
                <button type="submit" class="btn btn-danger">Delete Profile</button>
            </div>

            @if ($errors->any())
            <script>
                window.addEventListener('load', function() {
                    let errorMessage = "";
                    @foreach ($errors->all() as $error)
                        errorMessage += "{{ addslashes($error) }}\n"; 
                    @endforeach
                    if (errorMessage) {
                        alert(errorMessage.trim()); 
                    }
                });
            </script>
            @endif

        </form>

        <button id="delete-button" class="btn" onclick="confirmDelete()">Delete Profile</button>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/profile/show.js') }}"></script>
@endsection
