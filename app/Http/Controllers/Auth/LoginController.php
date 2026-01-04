<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    // Handle login form submission
    public function login(Request $request)
    {
        // Validate the form data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            return redirect()->intended('home'); 
        }        

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Show the forgot password form
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Handle forgot password form submission
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)
                    ->where('name', $request->name)
                    ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'No account found with the provided name and email.',
            ]);
        }

        $token = Str::random(60);
        \DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        // In a real application, send the reset link via email
        // For simplicity, we'll return the link directly
        $resetLink = route('password.reset', ['token' => $token, 'email' => $user->email]);

       // Store the reset link and success message separately in the session
       return back()->with([
            'message' => 'Password reset link generated successfully!',
            'reset_link' => $resetLink,
        ]);
    }

    // Show the reset password form
    public function showResetForm($token, Request $request)
    {
        return view('auth.passwords.reset', ['token' => $token, 'email' => $request->email]);
    }

    // Handle password reset form submission
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

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'No account found for this email.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        \DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->back()->with('success', 'Password reset successfully. Please login.');
    }
}
