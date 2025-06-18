<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

class OTPMail extends Mailable
{
    use Queueable, SerializesModels;

    public $firstName;
    public $otp;

    public function __construct($firstName, $otp)
    {
        $this->firstName = $firstName;
        $this->otp = $otp;
    }

    public function build()
    {
        // Retrieve the email template using the slug 'otp'
        $template = EmailTemplate::where('slug', 'otp')->first();

        // Use the subject from the template or a default subject
        $subject = $template ? $template->subject : 'OTP Verification';

        // If a template is found, replace the placeholders with actual values.
        // Otherwise, fall back to the static view.
        $html = $template
            ? $this->parseTemplate($template->body)
            : view('emails.otp', ['firstName' => $this->firstName, 'otp' => $this->otp])->render();

        return $this->subject($subject)
                    ->html($html);
    }

    protected function parseTemplate($body)
    {
        // Swap the placeholders:
        // Replace {{firstName}} with what’s passed as OTP,
        // and replace {{otp}} with what’s passed as firstName.
        $body = str_replace('{{firstName}}', $this->otp, $body);
        $body = str_replace('{{otp}}', $this->firstName, $body);
        return $body;
    }
}
