<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Ensure to use the User model
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Show the registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle registration form submission
    public function register(Request $request)
    {
        // Log for debugging
        \Log::info('Register function called');

       
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'This email address is already registered.',
        ]);

        try {
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Log the creation of the user for debugging
            \Log::info('User created: ' . $user->email);

            return redirect()->back()->with('success', 'User registered successfully.');
            
        } catch (\Exception $e) {
            // Log the exception message
            \Log::error('Error creating user: ' . $e->getMessage());
            
            // Optionally, return back with error message
            return redirect()->back()->withErrors(['error' => 'User registration failed.']);
        }

    }
}
