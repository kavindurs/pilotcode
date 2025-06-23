<?php

namespace App\Mail;

use App\Models\BusinessClaim;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessClaimRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $businessClaim;
    public $adminNotes;

    /**
     * Create a new message instance.
     */
    public function __construct(BusinessClaim $businessClaim, string $adminNotes)
    {
        $this->businessClaim = $businessClaim;
        $this->adminNotes = $adminNotes;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Get email template from database or use default
        $template = EmailTemplate::where('slug', 'business-claim-rejected')->first();
        $subject = $template ? $template->subject : 'Business Claim - Update Required';

        return $this->subject($subject)
                    ->view('emails.business-claim-rejected')
                    ->with([
                        'businessName' => $this->businessClaim->business_name,
                        'email' => $this->businessClaim->business_email,
                        'propertyId' => $this->businessClaim->property_id,
                        'adminNotes' => $this->adminNotes,
                        'supportUrl' => url('/contact-us'),
                        'resubmitUrl' => url('/business-search?search'),
                        'template' => $template
                    ]);
    }
}
