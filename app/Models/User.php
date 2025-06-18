<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class User extends Authenticatable implements CanResetPassword
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, CanResetPasswordTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Get the user's referral.
     */
    public function referral()
    {
        return $this->hasOne(Referral::class);
    }

    /**
     * Get the user's wallet.
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Get referral earnings where this user is the referrer.
     */
    public function referralEarnings()
    {
        return $this->hasMany(ReferralEarning::class, 'referrer_id');
    }

    /**
     * Get referral earnings where this user was referred.
     */
    public function referredEarnings()
    {
        return $this->hasMany(ReferralEarning::class, 'referred_user_id');
    }

    /**
     * Create or get the user's wallet.
     */
    public function getOrCreateWallet()
    {
        if (!$this->wallet) {
            $this->wallet()->create([
                'balance' => 0,
                'pending_balance' => 0,
                'total_earned' => 0,
                'total_withdrawn' => 0,
                'currency' => 'USD',
            ]);
            $this->load('wallet');
        }

        return $this->wallet;
    }

    /**
     * Create or get the user's referral.
     */
    public function getOrCreateReferral()
    {
        if (!$this->referral) {
            $this->referral()->create([
                'commission_rate' => 10.00, // Default 10% commission
                'is_active' => true,
            ]);
            $this->load('referral');
        }

        return $this->referral;
    }
}
