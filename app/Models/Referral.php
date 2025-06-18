<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\ReferralRate;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'referral_code',
        'referral_link',
        'commission_rate',
        'total_referrals',
        'total_earnings',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the referral.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the referral earnings for this referral.
     */
    public function earnings()
    {
        return $this->hasMany(ReferralEarning::class, 'referral_code', 'referral_code');
    }

    /**
     * Generate a unique referral code.
     */
    public static function generateReferralCode($userId)
    {
        do {
            $code = 'REF' . strtoupper(Str::random(8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Generate a referral link.
     */
    public static function generateReferralLink($code, $type = 'user')
    {
        if ($type === 'property') {
            return url('/property/create/step1?ref=' . $code);
        }
        return url('/register?ref=' . $code);
    }

    /**
     * Generate user registration referral link.
     */
    public function getUserReferralLink()
    {
        return self::generateReferralLink($this->referral_code, 'user');
    }

    /**
     * Generate property registration referral link.
     */
    public function getPropertyReferralLink()
    {
        return self::generateReferralLink($this->referral_code, 'property');
    }

    /**
     * Boot method to generate codes on creation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($referral) {
            if (empty($referral->referral_code)) {
                $referral->referral_code = self::generateReferralCode($referral->user_id);
            }
            if (empty($referral->referral_link)) {
                $referral->referral_link = self::generateReferralLink($referral->referral_code, 'user');
            }
        });
    }

    /**
     * Check if referral is valid and active.
     */
    public function isValid()
    {
        return $this->is_active &&
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Update referral statistics.
     */
    public function updateStats()
    {
        $this->total_referrals = $this->earnings()->count();
        $this->total_earnings = $this->earnings()->where('status', 'paid')->sum('commission_amount');
        $this->save();
    }

    /**
     * Get the current commission rate from the system.
     */
    public function getCommissionRate()
    {
        return ReferralRate::getCurrentRate();
    }
}
