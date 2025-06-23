<?php

namespace App\Mail;

use App\Models\BusinessClaim;
use App\Models\EmailTemplate;
use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessClaimApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $businessClaim;
    public $property;
    public $loginCredentials;

    /**
     * Create a new message instance.
     */
    public function __construct(BusinessClaim $businessClaim, Property $property, array $loginCredentials)
    {
        $this->businessClaim = $businessClaim;
        $this->property = $property;
        $this->loginCredentials = $loginCredentials;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Get email template from database or use default
        $template = EmailTemplate::where('slug', 'business-claim-approved')->first();
        $subject = $template ? $template->subject : 'Business Claim Approved - Login Details';

        return $this->subject($subject)
                    ->view('emails.business-claim-approved')
                    ->with([
                        'businessName' => $this->businessClaim->business_name,
                        'email' => $this->loginCredentials['email'],
                        'password' => $this->loginCredentials['password'],
                        'propertyId' => $this->property->id,
                        'loginUrl' => url('/property/login'),
                        'template' => $template
                    ]);
    }
}
