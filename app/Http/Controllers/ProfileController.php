<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::guard('web')->user(); 
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::guard('web')->user(); 
        $address = $user->address ?? new Address(); 

        return view('profile.edit', compact('user', 'address'));
    }

    public function update(Request $request)
    {
        $request->validate([
            // Validate each individual address field
            'address.first_name' => 'required|string|max:255',
            'address.last_name' => 'required|string|max:255',
            'address.address' => 'required|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.state' => 'required|string|max:255',
            'address.zip_code' => 'required|string|max:20',
            'address.country' => 'required|string|max:255',
        ]);

        // Get the authenticated user
        $user = Auth::guard('web')->user();

       // Combine address fields into one full address field
        $addressFull = $request->input('address.address') . ', ' . 
                        $request->input('address.city') . ', ' .
                        ($request->input('address.state') ? $request->input('address.state') . ', ' : '') .
                        ($request->input('address.zip_code') ? $request->input('address.zip_code') . ', ' : '') .
                        $request->input('address.country');


        // Update the user's profile details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Handle the address saving logic
        $address = $user->address()->first(); // Get the first address

        if ($address) {
            // Update the existing address
            $address->update(array_merge($request->input('address'), ['address_full' => $addressFull]));
        } else {
            // Create a new address
            $user->address()->create(array_merge($request->input('address'), ['address_full' => $addressFull]));
        }

        return redirect()->back()->with('success', 'Profile update successfully.');
    }

    public function destroy(Request $request)
    {
        $user = Auth::guard('web')->user(); 

        // Validate the entered password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'The password you entered is incorrect.']);
        }

        // Soft delete the user (keeps the user data in the database but marks it as deleted)
        $user->delete();

        // Log the user out
        Auth::logout();

        // Redirect to the home page as a guest
        return redirect()->route('home');
    }

}
