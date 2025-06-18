<?php

namespace App\Mail;

use App\Models\ReviewInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReviewInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The review invitation instance.
     *
     * @var \App\Models\ReviewInvitation
     */
    public $invitation;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\ReviewInvitation  $invitation
     * @return void
     */
    public function __construct(ReviewInvitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $reviewUrl = route('reviews.form', ['token' => $this->invitation->invitation_token]);

        // Replace placeholders in the message
        $message = $this->invitation->message;
        $message = str_replace('[Customer Name]', $this->invitation->customer_name, $message);
        $message = str_replace('[Review Link]', $reviewUrl, $message);

        $property = $this->invitation->property;
        $businessName = $property ? $property->business_name : 'Our Business';

        return $this->subject($this->invitation->subject)
                    ->view('emails.review-invitation')
                    ->with([
                        'customerName' => $this->invitation->customer_name,
                        'message' => $message,
                        'reviewUrl' => $reviewUrl,
                        'businessName' => $businessName,
                        'token' => $this->invitation->invitation_token,
                    ]);
    }
}
