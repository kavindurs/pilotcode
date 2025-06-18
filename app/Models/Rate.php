<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'user_id',
        'rate',
        'review',
        'status',
        'experienced_date'
    ];

    protected $casts = [
        'experienced_date' => 'date',
        'rate' => 'integer'
    ];

    /**
     * Get the property that was reviewed.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the user that wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
