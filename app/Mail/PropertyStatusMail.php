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
    public $action; // 'approved', 'rejected', 'approved_for_claim', 'rejected_for_claim', 'claimed'
    public $originalEmail; // Email without system-generated numbers
    public $newLoginEmail; // New login email for claimed properties
    public $newPassword; // New password for claimed properties

    public function __construct(Property $property, $action, $originalEmail = null, $newLoginEmail = null, $newPassword = null)
    {
        $this->property = $property;
        $this->action = $action;
        // Clean the email address if not provided or if it contains system-generated numbers
        $this->originalEmail = $originalEmail ?: $this->cleanEmailAddress($property->business_email);
        $this->newLoginEmail = $newLoginEmail;
        $this->newPassword = $newPassword;
    }

    public function build()
    {
        // Default subjects for different actions
        $subjects = [
            'approved' => 'Your Property "{{business_name}}" is Approved',
            'rejected' => 'Your Property "{{business_name}}" is Rejected',
            'approved_for_claim' => 'Your Property "{{business_name}}" is Approved for Claim',
            'rejected_for_claim' => 'Your Property "{{business_name}}" Claim Request is Rejected',
            'claimed' => 'Property "{{business_name}}" Claimed - New Login Details'
        ];

        $defaultSubject = $subjects[$this->action] ?? 'Property Status Update - {{business_name}}';

        // Try to get appropriate email template based on action
        $template = null;
        switch ($this->action) {
            case 'claimed':
                $template = EmailTemplate::where('slug', 'property_claimed')->first();
                break;
            case 'approved_for_claim':
            case 'rejected_for_claim':
            case 'approved':
            case 'rejected':
            default:
                $template = EmailTemplate::where('slug', 'property_status_update')->first();
                break;
        }

        // Fallback to ID 3 if no specific template found
        if (!$template) {
            $template = EmailTemplate::find(3);
        }

        // Use the subject from the template if available or fall back to default.
        $subject = $template ? $template->subject : $defaultSubject;

        // Parse placeholders in subject
        $subject = $this->parsePlaceholders($subject);

        // If a template is found, parse it to swap placeholders with actual values.
        // Otherwise, fall back to the static view.
        $html = $template
            ? $this->parseTemplate($template->body)
            : view('emails.property_status', [
                'property' => $this->property,
                'action'   => $this->formatAction($this->action),
                'originalEmail' => $this->originalEmail,
                'newLoginEmail' => $this->newLoginEmail,
                'newPassword' => $this->newPassword,
            ])->render();

        return $this->subject($subject)
                    ->html($html);
    }

    /**
     * Clean email address by removing system-generated numbers
     * @param string $email
     * @return string
     */
    private function cleanEmailAddress($email)
    {
        // Remove trailing numbers that were added by the system
        return preg_replace('/\d+$/', '', $email);
    }

    /**
     * Format action for display
     * @param string $action
     * @return string
     */
    private function formatAction($action)
    {
        switch ($action) {
            case 'approved':
                return 'Approved';
            case 'rejected':
                return 'Rejected';
            case 'approved_for_claim':
                return 'Approved for Claim';
            case 'rejected_for_claim':
                return 'Claim Request Rejected';
            case 'claimed':
                return 'Successfully Claimed';
            default:
                return ucfirst(str_replace('_', ' ', $action));
        }
    }

    /**
     * Parse placeholders in any text
     * @param string $text
     * @return string
     */
    private function parsePlaceholders($text)
    {
        $text = str_replace('{{first_name}}', $this->property->first_name ?? '', $text);
        $text = str_replace('{{last_name}}', $this->property->last_name ?? '', $text);
        $text = str_replace('{{business_name}}', $this->property->business_name ?? '', $text);
        $text = str_replace('{{action}}', $this->formatAction($this->action), $text);
        $text = str_replace('{{original_email}}', $this->originalEmail, $text);

        // For claimed properties, include new login details
        if ($this->action === 'claimed') {
            $text = str_replace('{{new_login_email}}', $this->newLoginEmail ?? '', $text);
            $text = str_replace('{{new_password}}', $this->newPassword ?? '', $text);
        }

        return $text;
    }

    protected function parseTemplate($body)
    {
        return $this->parsePlaceholders($body);
    }
}
