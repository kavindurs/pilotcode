<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'property_id',
        'plan_id',
        'referral_code',
        'plan_amount',
        'commission_rate',
        'commission_amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'plan_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the referrer (user who made the referral).
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the referred user.
     */
    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    /**
     * Get the property associated with this earning.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the plan associated with this earning.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Mark the earning as paid and update wallet.
     */
    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Update referrer's wallet
        $wallet = $this->referrer->wallet;
        if ($wallet) {
            $wallet->movePendingToBalance($this->commission_amount);
        }

        // Update referral stats
        $referral = Referral::where('referral_code', $this->referral_code)->first();
        if ($referral) {
            $referral->updateStats();
        }

        return $this;
    }

    /**
     * Calculate commission amount based on plan amount and rate.
     */
    public static function calculateCommission($planAmount, $commissionRate)
    {
        return ($planAmount * $commissionRate) / 100;
    }

    /**
     * Boot method to calculate commission on creation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($earning) {
            if (empty($earning->commission_amount)) {
                $earning->commission_amount = self::calculateCommission(
                    $earning->plan_amount,
                    $earning->commission_rate
                );
            }
        });
    }
}
