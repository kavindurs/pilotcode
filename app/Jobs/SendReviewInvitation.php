<?php

namespace App\Jobs;

use App\Models\ReviewInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReviewInvitationMail;

class SendReviewInvitation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The review invitation instance.
     *
     * @var \App\Models\ReviewInvitation
     */
    protected $invitation;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\ReviewInvitation  $invitation
     * @return void
     */
    public function __construct(ReviewInvitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Skip if already sent or expired
        if ($this->invitation->sent_at || $this->invitation->isExpired()) {
            return;
        }

        try {
            // Send the email
            Mail::to($this->invitation->customer_email)
                ->send(new ReviewInvitationMail($this->invitation));

            // Mark as sent
            $this->invitation->markAsSent();
        } catch (\Exception $e) {
            // Mark as failed
            $this->invitation->update([
                'status' => 'failed',
            ]);

            // Log the error
            \Log::error('Failed to send review invitation: ' . $e->getMessage(), [
                'invitation_id' => $this->invitation->id,
                'email' => $this->invitation->customer_email,
            ]);

            // Rethrow the exception if this is the final try
            if ($this->attempts() >= $this->tries) {
                throw $e;
            }
        }
    }
}
