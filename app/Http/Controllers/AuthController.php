<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    // ...existing code...

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            // Create directory if it doesn't exist
            if (!file_exists(storage_path('app/public/profile_pictures'))) {
                mkdir(storage_path('app/public/profile_pictures'), 0777, true);
            }

            // Handle profile picture upload
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $image = $request->file('profile_picture');
                // Generate a unique filename
                $filename = time() . '_' . $image->getClientOriginalName();
                // Store the image on the 'public' disk inside the "profile_pictures" folder
                // This will save the file to storage/app/public/profile_pictures
                $path = $image->storeAs('profile_pictures', $filename, 'public');
                // Save the relative path (without any extra "public/" prefix) in the database
                $profilePicturePath = 'profile_pictures/' . $filename;
            }

            // Extract domain from email for country detection (if needed)
            $emailDomain = substr(strrchr($request->email, "@"), 1);
            $country = 'Unknown';
            if (str_ends_with($emailDomain, '.lk')) {
                $country = 'Sri Lanka';
            } elseif (str_ends_with($emailDomain, '.in')) {
                $country = 'India';
            } elseif (str_ends_with($emailDomain, '.uk')) {
                $country = 'United Kingdom';
            }

            // Create the new user with the profile picture path saved
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'profile_picture' => $profilePicturePath,
                'country' => $country,
                'user_type' => 'regular user'
            ]);

            Auth::login($user);

            return redirect()->intended('dashboard');
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Registration failed. Please try again.'])->withInput();
        }
    }

    // ...existing code...
}

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;
use Exception;
use App\Http\Controllers\ReferralController;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        try {
            $status = Password::broker()->sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Password reset failed. Please try again.']);
        }
    }

    public function showResetForm(Request $request, $token)
    {
        return view('authontication.reset_password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        try {
            $status = Password::broker()->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            \Log::error('Password reset error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Password reset failed. Please try again.']);
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Extract domain from email for country detection
            $emailDomain = substr(strrchr($googleUser->email, "@"), 1);
            $country = 'Unknown';
            if (str_ends_with($emailDomain, '.lk')) {
                $country = 'Sri Lanka';
            } elseif (str_ends_with($emailDomain, '.in')) {
                $country = 'India';
            } elseif (str_ends_with($emailDomain, '.uk')) {
                $country = 'United Kingdom';
            }

            // Look for an existing user by email first
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // If google_id is not set, update it along with other info.
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'name'      => $googleUser->name,
                        'country'   => $country,
                        // Optionally update other fields as needed.
                    ]);
                }
            } else {
                // Create a new user for this Google login
                $user = User::create([
                    'google_id'  => $googleUser->id,
                    'name'       => $googleUser->name,
                    'email'      => $googleUser->email,
                    'password'   => Hash::make(Str::random(24)),
                    'country'    => $country,
                    'user_type'  => 'regular user'
                ]);
            }

            Auth::login($user);
            return redirect()->intended('dashboard');

        } catch (Exception $e) {
            Log::error('Google login error: ' . $e->getMessage());
            return redirect()->route('login')
                             ->withErrors(['error' => 'Google login failed. Please try again.']);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            // Create directory if it doesn't exist
            if (!file_exists(storage_path('app/public/profile_pictures'))) {
                mkdir(storage_path('app/public/profile_pictures'), 0777, true);
            }

            // Handle profile picture upload
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $image = $request->file('profile_picture');
                // Generate a unique filename
                $filename = time() . '_' . $image->getClientOriginalName();
                // Store the image on the 'public' disk inside the "profile_pictures" folder
                // This will save the file to storage/app/public/profile_pictures
                $path = $image->storeAs('profile_pictures', $filename, 'public');
                // Save the relative path (without any extra "public/" prefix) in the database
                $profilePicturePath = 'profile_pictures/' . $filename;
            }

            // Extract domain from email for country detection (if needed)
            $emailDomain = substr(strrchr($request->email, "@"), 1);
            $country = 'Unknown';
            if (str_ends_with($emailDomain, '.lk')) {
                $country = 'Sri Lanka';
            } elseif (str_ends_with($emailDomain, '.in')) {
                $country = 'India';
            } elseif (str_ends_with($emailDomain, '.uk')) {
                $country = 'United Kingdom';
            }

            // Create the new user with the profile picture path saved
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'profile_picture' => $profilePicturePath,
                'country' => $country,
                'user_type' => 'regular user',
                'referred_by' => $request->get('ref') // Store referral code if provided
            ]);

            // Process referral if code was provided
            if ($request->has('ref') && !empty($request->ref)) {
                ReferralController::processReferral($request->ref, $user->id);
            }

            Auth::login($user);

            return redirect()->intended('dashboard');
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Registration failed. Please try again.'])->withInput();
        }
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $user = Auth::user();

            if ($request->hasFile('profile_picture')) {
                // Create directory if it doesn't exist
                if (!file_exists(storage_path('app/public/profile_pictures'))) {
                    mkdir(storage_path('app/public/profile_pictures'), 0777, true);
                }

                $image = $request->file('profile_picture');
                $filename = $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();

                // Delete old profile picture if exists
                if ($user->profile_picture && file_exists(storage_path('app/public/' . $user->profile_picture))) {
                    unlink(storage_path('app/public/' . $user->profile_picture));
                }

                // Store new profile picture
                $path = $image->storeAs('profile_pictures', $filename, 'public');

                // Update database with new path
                User::where('id', $user->id)->update([
                    'profile_picture' => 'profile_pictures/' . $filename
                ]);

                return back()->with('success', 'Profile picture updated successfully');
            }

            return back()->with('error', 'No profile picture uploaded');
        } catch (\Exception $e) {
            Log::error('Profile picture update error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update profile picture');
        }
    }
}
