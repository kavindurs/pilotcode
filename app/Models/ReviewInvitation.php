<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReviewInvitation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property_id',
        'customer_name',
        'customer_email',
        'subject',
        'message',
        'status',
        'invitation_token',
        'sent_at',
        'opened_at',
        'clicked_at',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the property that owns the invitation.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Generate a unique invitation token.
     *
     * @return string
     */
    public static function generateToken()
    {
        return Str::random(40);
    }

    /**
     * Mark the invitation as sent.
     *
     * @return bool
     */
    public function markAsSent()
    {
        return $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark the invitation as opened.
     */
    public function markAsOpened()
    {
        if (!$this->opened_at) {
            $this->opened_at = now();
            $this->status = 'opened';
            $this->save();
        }
    }

    /**
     * Mark the invitation as clicked.
     */
    public function markAsClicked()
    {
        if (!$this->clicked_at) {
            $this->clicked_at = now();
            $this->status = 'clicked';
            $this->save();
        }
    }

    /**
     * Check if the invitation is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at && now()->gt($this->expires_at);
    }

    /**
     * Check if the invitation has been opened.
     */
    public function isOpened()
    {
        return $this->opened_at !== null;
    }

    /**
     * Check if the invitation link has been clicked.
     */
    public function isClicked()
    {
        return $this->clicked_at !== null;
    }

    /**
     * Get the status label for display.
     *
     * @return string
     */
    public function getStatusLabel()
    {
        if ($this->isExpired() && $this->status !== 'clicked') {
            return 'Expired';
        }

        return ucfirst($this->status);
    }

    /**
     * Get the status color class.
     *
     * @return string
     */
    public function getStatusColorClass()
    {
        if ($this->isExpired() && $this->status !== 'clicked') {
            return 'bg-gray-100 text-gray-800';
        }

        switch ($this->status) {
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'sent':
                return 'bg-blue-100 text-blue-800';
            case 'failed':
                return 'bg-red-100 text-red-800';
            case 'opened':
                return 'bg-purple-100 text-purple-800';
            case 'clicked':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
}
