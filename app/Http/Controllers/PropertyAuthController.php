<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\Hash;

class PropertyAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('property.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'business_email' => 'required|email',
            'password'       => 'required'
        ]);

        $property = Property::where('business_email', $credentials['business_email'])->first();

        if ($property && Hash::check($credentials['password'], $property->password)) {
            // Store the property id in the session to keep the owner logged in.
            session(['property_id' => $property->id]);
            return redirect()->route('property.dashboard')->with('success', 'Login successful!');
        }

        return back()->withErrors(['business_email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        session()->forget('property_id');
        return redirect()->route('property.login')->with('success', 'You have been logged out.');
    }
}
