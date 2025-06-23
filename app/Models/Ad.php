<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ad extends Model
{
    use HasFactory;

    protected $table = 'ads_simple';

    protected $fillable = [
        'property_id',
        'start_date',
        'end_date',
        'amount',  // Using the existing column
        'days',    // Using the existing column
        'daily_rate',
        'payment_status',
        'payment_intent_id',  // This is what exists in the table
        'transaction_id',
        'paid_at',  // This is what exists in the table
        'payment_notes',
        'total_amount',
        'total_days',
        'status',
        'admin_notes',
        'rejection_reason',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',  // Using the existing column
        'amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'daily_rate' => 'decimal:2'
    ];

    /**
     * Get the property that owns the ad
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the admin who approved the ad
     */
    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Scope a query to only include active ads
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', Carbon::now())
                    ->where('end_date', '>=', Carbon::now());
    }

    /**
     * Scope for ads that should be displayed on homepage (active ads with valid dates)
     */
    public function scopeForHomepage($query)
    {
        return $query->active()->with('property');
    }

    /**
     * Scope a query to only include pending ads
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved ads
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Check if the ad is currently active
     */
    public function isActive()
    {
        return $this->status === 'active' &&
               Carbon::now()->between($this->start_date, $this->end_date);
    }

    /**
     * Check if the ad has expired
     */
    public function isExpired()
    {
        return Carbon::now()->gt($this->end_date);
    }

    /**
     * Get the status badge class for UI
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'active' => 'bg-green-500/20 text-green-400 border-green-500/30',
            'approved' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
            'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
            'payment_pending' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
            'rejected' => 'bg-red-500/20 text-red-400 border-red-500/30',
            'paused' => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
            'expired' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
            default => 'bg-gray-500/20 text-gray-400 border-gray-500/30'
        };
    }

    /**
     * Get formatted budget
     */
    public function getFormattedBudgetAttribute()
    {
        return '$' . number_format($this->budget, 2);
    }

    /**
     * Get click-through rate
     */
    public function getClickThroughRateAttribute()
    {
        if ($this->total_views == 0) {
            return 0;
        }
        return round(($this->total_clicks / $this->total_views) * 100, 2);
    }

    /**
     * Increment ad views
     */
    public function incrementViews()
    {
        $this->increment('total_views');
    }

    /**
     * Increment ad clicks
     */
    public function incrementClicks()
    {
        $this->increment('total_clicks');
    }
}
