<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'business_email',
        'plan_id',
        'order_id',
        'amount',
        'currency',
        'status',
        'transaction_id',
        'payment_method',
        'genie_customer_id',
        'genie_transaction_id',
        'payment_url',
        'customer_name',
        'customer_email',
        'tokenize',
        'completed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tokenize' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // Accessors & Mutators
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'PENDING' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-green-100 text-green-800',
            'CONFIRMED' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'FAILED' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'CANCELLED' => 'bg-gray-100 text-gray-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['confirmed', 'CONFIRMED', 'success']);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'PENDING']);
    }

    public function scopeFailed($query)
    {
        return $query->whereIn('status', ['failed', 'FAILED']);
    }

    public function scopeCancelled($query)
    {
        return $query->whereIn('status', ['cancelled', 'CANCELLED']);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'CONFIRMED', 'success']);
    }

    // Helper method to get active plan for a customer (by email or property ID)
    public static function getActivePlanForCustomer($businessEmail, $propertyId = null)
    {
        $query = self::query();

        if ($propertyId) {
            // If property ID is provided, search by both property_id and business_email
            $query->where(function($q) use ($propertyId, $businessEmail) {
                $q->where('property_id', $propertyId)
                  ->orWhere('business_email', $businessEmail);
            })->orderBy('property_id', 'desc'); // Prefer records with property_id
        } else {
            // Fallback to business_email only
            $query->where('business_email', $businessEmail);
        }

        return $query->active()
            ->with('plan')
            ->first();
    }

    // Helper method to check if customer has active plan (by email or property ID)
    public static function hasActivePlan($businessEmail, $propertyId = null)
    {
        $query = self::query();

        if ($propertyId) {
            // If property ID is provided, search by both property_id and business_email
            $query->where(function($q) use ($propertyId, $businessEmail) {
                $q->where('property_id', $propertyId)
                  ->orWhere('business_email', $businessEmail);
            });
        } else {
            // Fallback to business_email only
            $query->where('business_email', $businessEmail);
        }

        return $query->active()->exists();
    }

    // Helper method to find existing payment record for a property (for plan switching)
    public static function findExistingPaymentForProperty($businessEmail, $propertyId = null)
    {
        if ($propertyId) {
            // First try to find by property_id
            $payment = self::where('property_id', $propertyId)->first();
            if ($payment) {
                return $payment;
            }
        }

        // Fall back to business_email
        return self::where('business_email', $businessEmail)->first();
    }
}
