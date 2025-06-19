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
        'google_id',
        'profile_picture',
        'country',
        'user_type',
        'company_name',
        'is_business',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'email_otp',
        'email_otp_expires_at',
        'is_verified',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
        'two_factor_enabled' => 'boolean',
        'two_factor_confirmed_at' => 'datetime',
        'two_factor_recovery_codes' => 'array',
        'email_otp_expires_at' => 'datetime',
        'is_verified' => 'boolean',
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

    /**
     * Check if two-factor authentication is enabled and confirmed.
     *
     * @return bool
     */
    public function hasTwoFactorEnabled()
    {
        return $this->two_factor_enabled && !is_null($this->two_factor_confirmed_at);
    }

    /**
     * Get the QR code for two-factor authentication setup.
     *
     * @return string
     */
    public function getTwoFactorQrCodeAttribute()
    {
        if (!$this->two_factor_secret) {
            return null;
        }

        return app('pragmarx.google2fa')->getQRCodeInline(
            config('app.name'),
            $this->email,
            $this->two_factor_secret
        );
    }

    /**
     * Generate recovery codes for two-factor authentication.
     *
     * @return array
     */
    public function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(substr(md5(uniqid()), 0, 8));
        }

        $this->two_factor_recovery_codes = $codes;
        $this->save();

        return $codes;
    }

    /**
     * Verify a two-factor authentication code.
     *
     * @param string $code
     * @return bool
     */
    public function verifyTwoFactorCode($code)
    {
        if (!$this->two_factor_secret) {
            return false;
        }

        // Check if it's a recovery code
        if ($this->isValidRecoveryCode($code)) {
            return true;
        }

        // Check TOTP code
        return app('pragmarx.google2fa')->verifyKey($this->two_factor_secret, $code);
    }

    /**
     * Check if the provided code is a valid recovery code.
     *
     * @param string $code
     * @return bool
     */
    public function isValidRecoveryCode($code)
    {
        if (!$this->two_factor_recovery_codes) {
            return false;
        }

        $codes = $this->two_factor_recovery_codes;
        $key = array_search(strtoupper($code), $codes);

        if ($key !== false) {
            // Remove the used recovery code
            unset($codes[$key]);
            $this->two_factor_recovery_codes = array_values($codes);
            $this->save();
            return true;
        }

        return false;
    }

    /**
     * Generate and store OTP for email verification.
     *
     * @return string
     */
    public function generateEmailOtp()
    {
        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $this->email_otp = $otp;
        $this->email_otp_expires_at = now()->addMinutes(10); // OTP expires in 10 minutes
        $this->save();

        return $otp;
    }

    /**
     * Verify the provided OTP.
     *
     * @param string $otp
     * @return bool
     */
    public function verifyEmailOtp($otp)
    {
        if (!$this->email_otp || !$this->email_otp_expires_at) {
            return false;
        }

        if (now()->isAfter($this->email_otp_expires_at)) {
            // OTP has expired, clear it
            $this->clearEmailOtp();
            return false;
        }

        if ($this->email_otp === $otp) {
            // OTP is valid, mark user as verified and clear OTP
            $this->is_verified = true;
            $this->email_verified_at = now();
            $this->clearEmailOtp();
            return true;
        }

        return false;
    }

    /**
     * Clear OTP data.
     *
     * @return void
     */
    public function clearEmailOtp()
    {
        $this->email_otp = null;
        $this->email_otp_expires_at = null;
        $this->save();
    }

    /**
     * Check if user has verified their email.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return $this->is_verified && $this->email_verified_at;
    }

    /**
     * Check if OTP is still valid (not expired).
     *
     * @return bool
     */
    public function hasValidOtp()
    {
        return $this->email_otp &&
               $this->email_otp_expires_at &&
               now()->isBefore($this->email_otp_expires_at);
    }
}
