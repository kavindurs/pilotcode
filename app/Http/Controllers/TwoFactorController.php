<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    /**
     * Show the two-factor authentication challenge form.
     */
    public function challenge()
    {
        $user = Auth::user();

        if (!$user || !$user->hasTwoFactorEnabled()) {
            return redirect()->route('dashboard');
        }

        return view('auth.two-factor-challenge');
    }

    /**
     * Verify the two-factor authentication code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = Auth::user();

        if (!$user || !$user->hasTwoFactorEnabled()) {
            return redirect()->route('dashboard');
        }

        if (!$user->verifyTwoFactorCode($request->code)) {
            return back()->withErrors([
                'code' => 'The provided two-factor authentication code was invalid.'
            ]);
        }

        // Mark 2FA as verified for this session
        $request->session()->put('2fa_verified', $user->id);

        return redirect()->intended(route('dashboard'));
    }
}
