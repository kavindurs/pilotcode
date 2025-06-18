<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;
use App\Models\Rate;

class ReviewStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $review;
    public $action;

    public function __construct(Rate $review, $action)
    {
        $this->review = $review;
        $this->action = $action;
    }

    public function build()
    {
        // Retrieve the email template by its slug ("review_status")
        $template = EmailTemplate::where('slug', 'review_status')->first();

        if (!$template) {
            abort(500, 'Review email template not found in database.');
        }

        // Replace the {{action}} placeholder with the actual action value
        $body = str_replace('{{action}}', $this->action, $template->body);

        return $this->subject($template->subject)
                    ->html($body);
    }
}
