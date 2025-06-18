<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Property;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\TextPart;
use Symfony\Component\Mime\Part\HtmlPart;

class PropertyPasswordResetController extends Controller
{
    // Show the form to request a password reset link.
    public function showLinkRequestForm()
    {
        return view('property.password.email');
    }

    // Handle a reset link request.
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'business_email' => 'required|email|exists:properties,business_email',
        ]);

        $email = $request->business_email;
        $plainToken = Str::random(64);

        // Store or update the token in the database (token hashed for security)
        DB::table('property_password_resets')->updateOrInsert(
            ['business_email' => $email],
            [
                'token' => Hash::make($plainToken),
                'created_at' => Carbon::now()
            ]
        );

        // Generate the reset link using the plain token.
        $resetLink = route('property.password.reset.form', [
            'token' => $plainToken,
            'business_email' => $email,
        ]);

        // Retrieve the email template from the database.
        $template = \App\Models\EmailTemplate::where('slug', 'property_password_reset')->first();
        if (!$template) {
            abort(500, 'Password reset email template not found.');
        }

        // Replace the placeholder with the actual reset link.
        $body = str_replace('{{resetLink}}', $resetLink, $template->body);

        // Send the email by setting the HTML body.
        Mail::send([], [], function($message) use ($email, $template, $body) {
            $message->to($email)
                    ->subject($template->subject)
                    ->html($body);
        });

        return back()->with('success', 'We have emailed your password reset link!');
    }

    // Show the password reset form.
    public function showResetForm(Request $request)
    {
        // Retrieve token and email from query parameters.
        $token = $request->token;
        $email = $request->business_email;
        return view('property.password.reset', compact('token', 'email'));
    }

    // Reset the password.
    public function reset(Request $request)
    {
        $request->validate([
            'business_email' => 'required|email|exists:properties,business_email',
            'token'          => 'required',
            'password'       => 'required|confirmed|min:6',
        ]);

        $email = $request->business_email;
        $record = DB::table('property_password_resets')
                    ->where('business_email', $email)
                    ->first();

        if (!$record) {
            return back()->withErrors(['business_email' => 'No password reset record found.']);
        }

        // Check that the provided plain token matches the hashed token.
        if (!Hash::check($request->token, $record->token)) {
            return back()->withErrors(['token' => 'Invalid token.']);
        }

        // Optional: Check if the token has expired (e.g. valid for 60 minutes).
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('property_password_resets')->where('business_email', $email)->delete();
            return back()->withErrors(['token' => 'Token expired. Please request a new reset link.']);
        }

        // Update the property password.
        $property = Property::where('business_email', $email)->first();
        $property->password = Hash::make($request->password);
        $property->save();

        // Delete the reset record.
        DB::table('property_password_resets')->where('business_email', $email)->delete();

        return redirect()->route('property.login')->with('success', 'Your password has been reset successfully. Please login with your new password.');
    }
}
