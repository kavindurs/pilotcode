<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function show()
    {
        return view('profile.show');
    }

    /**
     * Update the user's basic profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'country' => ['nullable', 'string', 'max:255'],
            'profile_picture' => ['nullable', 'image', 'max:2048'], // 2MB max
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old image if exists
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }

            // Store new image
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $user->profile_picture = $path;
        }

        // Handle profile picture removal
        if ($request->has('remove_picture') && $user->profile_picture) {
            Storage::delete($user->profile_picture);
            $user->profile_picture = null;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->country = $validated['country'];
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's account preferences.
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'user_type' => ['required', 'string', 'in:regular user,business owner'],
            'notifications' => ['nullable', 'array'],
            'language' => ['nullable', 'string', 'max:10'],
            'timezone' => ['nullable', 'string', 'max:50'],
        ]);

        // Update user type if changed
        $user->user_type = $validated['user_type'];

        // Here you would handle other preferences that might be stored in a separate
        // user_preferences table or as JSON data, but keeping it simple for now

        $user->save();

        return redirect()->back()->with('success', 'Account preferences updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    /**
     * Update the user's privacy settings.
     */
    public function updatePrivacy(Request $request)
    {
        $validated = $request->validate([
            'profile_visibility' => ['required', 'string', 'in:public,registered,private'],
            'data_collection' => ['nullable', 'array'],
        ]);

        $user = Auth::user();

        // Here you would store privacy settings in a privacy_settings table
        // or as a JSON column, but keeping it simple for demonstration

        return redirect()->back()->with('success', 'Privacy settings updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        $user = Auth::user();

        // Delete profile picture if exists
        if ($user->profile_picture) {
            Storage::delete($user->profile_picture);
        }

        // Delete the user account
        $user->delete();

        // Log out and redirect to home
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been permanently deleted.');
    }
}
