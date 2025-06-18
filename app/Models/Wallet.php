<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'pending_balance',
        'total_earned',
        'total_withdrawn',
        'currency',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Add money to the wallet.
     */
    public function addMoney($amount, $type = 'balance')
    {
        $this->increment($type, $amount);

        if ($type === 'balance') {
            $this->increment('total_earned', $amount);
        }

        return $this;
    }

    /**
     * Withdraw money from the wallet.
     */
    public function withdrawMoney($amount)
    {
        if ($this->balance >= $amount) {
            $this->decrement('balance', $amount);
            $this->increment('total_withdrawn', $amount);
            return true;
        }

        return false;
    }

    /**
     * Move money from pending to available balance.
     */
    public function movePendingToBalance($amount)
    {
        if ($this->pending_balance >= $amount) {
            $this->decrement('pending_balance', $amount);
            $this->increment('balance', $amount);
            $this->increment('total_earned', $amount);
            return true;
        }

        return false;
    }

    /**
     * Get formatted balance.
     */
    public function getFormattedBalanceAttribute()
    {
        return $this->currency . ' ' . number_format($this->balance, 2);
    }

    /**
     * Get formatted pending balance.
     */
    public function getFormattedPendingBalanceAttribute()
    {
        return $this->currency . ' ' . number_format($this->pending_balance, 2);
    }
}
