<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralRate extends Model
{
    protected $table = 'referral_rate';

    protected $fillable = [
        'rate',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
    ];

    public $timestamps = false;

    /**
     * Get the current referral rate.
     */
    public static function getCurrentRate()
    {
        $rateRecord = self::first();
        return $rateRecord ? $rateRecord->rate : 10.00; // Default to 10% if no rate is set
    }
}
