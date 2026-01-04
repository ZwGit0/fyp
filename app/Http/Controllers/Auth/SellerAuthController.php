<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SellerAuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.seller.sellerRegister');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:sellers,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'This email address is already registered.',
        ]);

        $addressFull = $request->input('address') . ', ' . 
                        $request->input('city') . ', ' . 
                        ($request->input('state') ? $request->input('state') . ', ' : '') . 
                        ($request->input('zip_code') ? $request->input('zip_code') . ', ' : '') . 
                        $request->input('country');
                        
        $seller = Seller::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address, 
            'address_full' => $addressFull,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('seller')->login($seller);

        return redirect()->back()->with('success', 'Seller account registered successfully.');
    }

    public function showLoginForm()
    {
        return view('auth.seller.sellerLogin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('seller')->attempt($credentials)) {
            return redirect()->route('seller.dashboard');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function logout()
    {
        Auth::guard('seller')->logout();
        return redirect()->route('seller.welcome');
    }

    // Show the forgot password form for sellers
    public function showLinkRequestForm()
    {
        return view('auth.seller.sellerForgotPassword');
    }

    // Handle forgot password form submission for sellers
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        $seller = Seller::where('email', $request->email)
                    ->where('name', $request->name)
                    ->first();

        if (!$seller) {
            return back()->withErrors([
                'email' => 'No seller account found with the provided name and email.',
            ]);
        }

        $token = Str::random(60);
        \DB::table('password_resets')->updateOrInsert(
            ['email' => $seller->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        $resetLink = route('seller.password.reset', ['token' => $token, 'email' => $seller->email]);

        return back()->with([
            'message' => 'Password reset link generated successfully!',
            'reset_link' => $resetLink,
        ]);
    }

    // Show the reset password form for sellers
    public function showResetForm($token, Request $request)
    {
        return view('auth.seller.sellerResetPassword', ['token' => $token, 'email' => $request->email]);
    }
 
    // Handle password reset form submission for sellers
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        $reset = \DB::table('password_resets')
                    ->where('email', $request->email)
                    ->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        $seller = Seller::where('email', $request->email)->first();
        if (!$seller) {
            return back()->withErrors(['email' => 'No seller account found for this email.']);
        }

        $seller->password = Hash::make($request->password);
        $seller->save();

        \DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->back()->with('success', 'Password reset successfully. Please login.');
    }
}
