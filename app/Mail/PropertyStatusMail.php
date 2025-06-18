<?php

namespace App\Mail;

use App\Models\Property;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PropertyStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $property;
    public $action; // 'approved' or 'rejected'

    public function __construct(Property $property, $action)
    {
        $this->property = $property;
        $this->action = $action;
    }

    public function build()
    {
        // Default subject if no database template is found.
        $defaultSubject = $this->action === 'approved'
            ? 'Your Property is Approved'
            : 'Your Property is Rejected';

        // Retrieve the email template using the ID 3 from the database.
        $template = EmailTemplate::find(3);

        // Use the subject from the template if available or fall back to default.
        $subject = $template ? $template->subject : $defaultSubject;

        // If a template is found, parse it to swap placeholders with actual values.
        // Otherwise, fall back to the static view.
        $html = $template
            ? $this->parseTemplate($template->body)
            : view('emails.property_status', [
                'property' => $this->property,
                'action'   => $this->action,
            ])->render();

        return $this->subject($subject)
                    ->html($html);
    }

    protected function parseTemplate($body)
    {
        // Replace the custom placeholders in the template with actual data.
        // Ensure that your email template uses these exact placeholders.
        $body = str_replace('{{first_name}}', $this->property->first_name ?? '', $body);
        $body = str_replace('{{last_name}}',  $this->property->last_name ?? '', $body);
        $body = str_replace('{{business_name}}', $this->property->business_name ?? '', $body);
        $body = str_replace('{{action}}', ucfirst($this->action), $body);
        return $body;
    }
}
