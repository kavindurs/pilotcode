<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\ReviewInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReviewInvitation as ReviewInvitationMail;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ReviewInvitationController extends Controller
{
    /**
     * Display a listing of the invitations.
     */
    public function index()
    {
        // Get the property ID from the session
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to access your review invitations.');
        }

        // Retrieve the property
        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        // Get active plan and email limit
        $activePlan = $property->getActivePlan();
        $emailLimit = $this->getEmailLimit($activePlan);

        // Get emails used this month
        $emailsUsed = ReviewInvitation::where('property_id', $propertyId)
                          ->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year)
                          ->count();

        // Get invitations for this property with pagination
        $invitations = ReviewInvitation::where('property_id', $propertyId)
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);

        return view('property.invitations', compact(
            'invitations',
            'property',
            'activePlan',
            'emailLimit',
            'emailsUsed'
        ));
    }

    /**
     * Show the form for creating a new invitation.
     */
    public function create()
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to create invitations.');
        }

        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        // Check active plan
        $activePlan = $property->getActivePlan();

        // If Free plan, redirect with message
        if (!$activePlan || $activePlan->name === 'Free') {
            return redirect()->route('property.invitations')
                ->with('error', 'Your current plan does not include review invitations. Please upgrade to send invitations.');
        }

        // Check if there's room for more emails this month
        $emailLimit = $this->getEmailLimit($activePlan);

        $emailsUsed = ReviewInvitation::where('property_id', $propertyId)
                          ->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year)
                          ->count();

        if ($emailsUsed >= $emailLimit) {
            return redirect()->route('property.invitations')
                ->with('error', 'You have reached your monthly email limit. Please upgrade your plan to send more invitations.');
        }

        return view('property.invitation-form', compact('property'));
    }

    /**
     * Show the form for bulk creating invitations.
     */
    public function bulkCreate()
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to create invitations.');
        }

        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        // Check if there's room for more emails this month
        $activePlan = $property->getActivePlan();
        $emailLimit = $this->getEmailLimit($activePlan);

        $emailsUsed = ReviewInvitation::where('property_id', $propertyId)
                          ->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year)
                          ->count();

        if ($emailsUsed >= $emailLimit) {
            return redirect()->route('property.invitations')
                ->with('error', 'You have reached your monthly email limit. Please upgrade your plan to send more invitations.');
        }

        return view('property.invitation-bulk', compact('property', 'emailLimit', 'emailsUsed'));
    }

    /**
     * Store a newly created invitation.
     */
    public function store(Request $request)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to create invitations.');
        }

        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        // Check if there's room for more emails this month
        $activePlan = $property->getActivePlan();
        $emailLimit = $this->getEmailLimit($activePlan);

        $emailsUsed = ReviewInvitation::where('property_id', $propertyId)
                          ->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year)
                          ->count();

        if ($emailsUsed >= $emailLimit) {
            return redirect()->route('property.invitations')
                ->with('error', 'You have reached your monthly email limit. Please upgrade your plan to send more invitations.');
        }

        // Validate
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'expires_days' => 'required|integer|min:1|max:365',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Fix: Cast expires_days to integer
        $expiresDays = (int) $request->input('expires_days', 30);

        // Generate unique token
        $token = Str::random(40);

        // Create invitation
        $invitation = new ReviewInvitation();
        $invitation->property_id = $propertyId;
        $invitation->customer_name = $request->customer_name;
        $invitation->customer_email = $request->customer_email;
        $invitation->subject = $request->subject ?? "Please share your experience with {$property->business_name}";
        $invitation->message = $request->message;
        $invitation->status = 'pending';
        $invitation->invitation_token = Str::random(40);
        $invitation->expires_at = now()->addDays((int)$request->expires_days);
        $invitation->save();

        // Try sending email directly (not queued) with detailed debugging
        try {
            \Log::info("Attempting to send email to: {$invitation->customer_email}");

            // Create mail object first
            $mail = new ReviewInvitationMail(
                $invitation->customer_name,
                $property->business_name,
                $invitation->message, // This should remain the same in your controller
                url('/review/' . $invitation->invitation_token),
                $invitation->id
            );

            // Force synchronous sending (bypass queue)
            Mail::mailer(config('mail.default'))
                ->to($invitation->customer_email)
                ->send($mail);  // Use send() instead of queue()

            // If we get here, email was sent successfully
            $invitation->status = 'sent';
            $invitation->sent_at = now();
            $invitation->save();

            \Log::info("Email sent successfully to: {$invitation->customer_email}");

            return redirect()->route('property.invitations')
                ->with('success', 'Review invitation has been sent successfully.');
        }
        catch (\Exception $e) {
            // Log detailed error information
            \Log::error('Email error: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());

            $invitation->status = 'failed';
            $invitation->save();

            return redirect()->route('property.invitations')
                ->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified invitation.
     */
    public function show($id)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to view invitations.');
        }

        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        $invitation = ReviewInvitation::find($id);

        if (!$invitation) {
            return redirect()->route('property.invitations')
                ->with('error', 'Invitation not found.');
        }

        // Check if this invitation belongs to the property
        if ($invitation->property_id != $propertyId) {
            return redirect()->route('property.invitations')
                ->with('error', 'You do not have permission to view this invitation.');
        }

        return view('property.invitation-detail', compact('invitation', 'property'));
    }

    /**
     * Remove the specified invitation.
     */
    public function destroy($id)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to delete invitations.');
        }

        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        $invitation = ReviewInvitation::find($id);

        if (!$invitation) {
            return redirect()->route('property.invitations')
                ->with('error', 'Invitation not found.');
        }

        // Check if this invitation belongs to the property
        if ($invitation->property_id != $propertyId) {
            return redirect()->route('property.invitations')
                ->with('error', 'You do not have permission to delete this invitation.');
        }

        // Delete invitation
        $invitation->delete();

        return redirect()->route('property.invitations')
            ->with('success', 'Invitation deleted successfully.');
    }

    /**
     * Resend the specified invitation.
     */
    public function resend($id)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to resend invitations.');
        }

        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        $invitation = ReviewInvitation::find($id);

        if (!$invitation) {
            return redirect()->route('property.invitations')
                ->with('error', 'Invitation not found.');
        }

        // Check if this invitation belongs to the property
        if ($invitation->property_id != $propertyId) {
            return redirect()->route('property.invitations')
                ->with('error', 'You do not have permission to resend this invitation.');
        }

        // Check if invitation has expired
        if ($invitation->expires_at && now()->gt($invitation->expires_at)) {
            // Fix: Use an integer value for days
            $invitation->expires_at = now()->addDays(30);
        }

        // Resend email
        try {
            Mail::to($invitation->customer_email)->send(new ReviewInvitationMail(
                $invitation->customer_name,
                $property->business_name,
                $invitation->message,
                url('/review/' . $invitation->invitation_token),
                $invitation->id
            ));

            // Update status
            $invitation->status = 'sent';
            $invitation->sent_at = now();
            $invitation->save();

            return redirect()->route('property.invitations.show', $invitation->id)
                ->with('success', 'Invitation has been resent successfully.');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Failed to resend review invitation: ' . $e->getMessage());

            // Update status
            $invitation->status = 'failed';
            $invitation->save();

            return redirect()->route('property.invitations.show', $invitation->id)
                ->with('error', 'Failed to resend the invitation. Please try again later.');
        }
    }

    /**
     * Process bulk invitation sending.
     */
    public function bulkSend(Request $request)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to send bulk invitations.');
        }

        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        // Validate file upload
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            'default_message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if there's room for more emails this month
        $activePlan = $property->getActivePlan();
        $emailLimit = $this->getEmailLimit($activePlan);

        $emailsUsed = ReviewInvitation::where('property_id', $propertyId)
                          ->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year)
                          ->count();

        $remaining = $emailLimit - $emailsUsed;

        if ($remaining <= 0) {
            return redirect()->route('property.invitations')
                ->with('error', 'You have reached your monthly email limit. Please upgrade your plan to send more invitations.');
        }

        // Process CSV file
        $path = $request->file('csv_file')->getRealPath();
        $records = array_map('str_getcsv', file($path));

        // Get headers (first row)
        $headers = array_shift($records);

        // Normalize headers (case-insensitive)
        $headers = array_map('strtolower', $headers);

        // Find column indexes
        $nameIndex = array_search('name', $headers);
        $emailIndex = array_search('email', $headers);
        $messageIndex = array_search('message', $headers);

        if ($nameIndex === false || $emailIndex === false) {
            return redirect()->back()
                ->with('error', 'Invalid CSV format. The file must contain "name" and "email" columns.');
        }

        // Process records
        $sentCount = 0;
        $failedCount = 0;
        $errors = [];

        // Fix: Define a default expiration in days as an integer
        $expiresDays = (int) $request->input('expires_days', 30);

        foreach ($records as $index => $record) {
            // Skip if we've reached the limit
            if ($sentCount + $emailsUsed >= $emailLimit) {
                break;
            }

            // Skip empty rows
            if (empty($record[$nameIndex]) || empty($record[$emailIndex])) {
                continue;
            }

            $name = $record[$nameIndex];
            $email = $record[$emailIndex];
            $message = ($messageIndex !== false && !empty($record[$messageIndex])) ?
                        $record[$messageIndex] : $defaultMessage;

            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $failedCount++;
                continue;
            }

            // Generate unique token
            $token = Str::random(40);

            // Create invitation
            $invitation = new ReviewInvitation();
            $invitation->property_id = $propertyId;
            $invitation->customer_name = $name;
            $invitation->customer_email = $email;
            $invitation->subject = "Please share your experience with {$property->business_name}";
            $invitation->message = $message;
            $invitation->status = 'pending';
            $invitation->invitation_token = Str::random(40);
            $invitation->expires_at = now()->addDays($expiresDays);
            $invitation->save();

            // Send email
            try {
                // Create mail object first
                $mail = new ReviewInvitationMail(
                    $name,
                    $property->business_name,
                    $message,
                    url('/review/' . $invitation->invitation_token),
                    $invitation->id
                );

                Mail::to($email)->send($mail);

                // Update status
                $invitation->status = 'sent';
                $invitation->sent_at = now();
                $invitation->save();

                $sentCount++;
            }
            catch (\Exception $e) {
                // Log detailed error
                \Log::error("Failed to send invitation to {$email}: " . $e->getMessage());

                // Update status
                $invitation->status = 'failed';
                $invitation->save();

                $failedCount++;
                $errors[] = "Row {$index}: {$email} - {$e->getMessage()}";
            }
        }

        $message = "Bulk processing complete. {$sentCount} invitations sent successfully.";

        if ($failedCount > 0) {
            $message .= " {$failedCount} invitations failed to send.";

            // Log all errors for reference
            \Log::error("Bulk invitation failures: " . implode("; ", $errors));
        }

        if ($sentCount + $emailsUsed >= $emailLimit) {
            $message .= " You have reached your monthly email limit.";
        }

        return redirect()->route('property.invitations')
            ->with('success', $message);
    }

    /**
     * Get the email limit based on the plan.
     */
    private function getEmailLimit($plan)
    {
        if (!$plan) {
            return 0; // Default to 0 emails if no plan
        }

        switch ($plan->name) {
            case 'Free':
                return 0; // Free plan: 0 emails per month
            case 'Basic':
                return 30; // Basic plan: 30 emails per month
            case 'Pro':
                return 75; // Pro plan: 75 emails per month
            case 'Premium':
                return 200; // Premium plan: 200 emails per month
            default:
                return 0; // Default to 0 for unknown plans
        }
    }

    /**
     * Track email opens.
     */
    public function trackOpen($id)
    {
        try {
            $invitation = ReviewInvitation::find($id);

            if ($invitation) {
                $invitation->markAsOpened();
            }

            // Return a 1x1 transparent GIF
            $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
            return response($pixel, 200)->header('Content-Type', 'image/gif');
        } catch (\Exception $e) {
            \Log::error("Failed to track email open: " . $e->getMessage());
            return response('', 200);
        }
    }

    /**
     * Show the review form for customers.
     */
    public function showReviewForm($token)
    {
        try {
            $invitation = ReviewInvitation::where('invitation_token', $token)->first();

            if (!$invitation) {
                return abort(404, 'Invitation not found');
            }

            // Check if expired
            if ($invitation->isExpired()) {
                return view('review.expired', ['businessName' => $invitation->property->business_name]);
            }

            // Mark as clicked
            $invitation->markAsClicked();

            // Get property info
            $property = $invitation->property;

            return view('review.form', [
                'invitation' => $invitation,
                'property' => $property
            ]);
        } catch (\Exception $e) {
            \Log::error("Failed to show review form: " . $e->getMessage());
            return abort(500, 'An error occurred');
        }
    }
}
