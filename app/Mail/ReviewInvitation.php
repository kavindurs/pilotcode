<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $customerName;
    public $businessName;
    public $messageContent; // Changed variable name from 'message' to 'messageContent'
    public $reviewUrl;
    public $invitationId;

    // Add these properties to your ReviewInvitation class
    public $tries = 3;
    public $timeout = 60;
    public $maxExceptions = 3;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customerName, $businessName, $messageContent, $reviewUrl, $invitationId)
    {
        $this->customerName = $customerName;
        $this->businessName = $businessName;
        $this->messageContent = $messageContent; // Changed variable name
        $this->reviewUrl = $reviewUrl;
        $this->invitationId = $invitationId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Please share your experience with {$this->businessName}")
                    ->from(config('mail.from.address'), $this->businessName) // Fixed from() parameters order
                    ->view('emails.review-invitation')
                    ->with([
                        'customerName' => $this->customerName,
                        'businessName' => $this->businessName,
                        'messageContent' => $this->messageContent, // Changed variable name
                        'reviewUrl' => $this->reviewUrl,
                        'trackingUrl' => url("/track-email/{$this->invitationId}")
                    ]);
    }
}
