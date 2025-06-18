<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'product_limit',
        'widget_limit',
        'html_integration_limit',
        'review_invitation_limit',
        'referral_code',
    ];

    protected $casts = [
        'referral_code' => 'boolean',
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
