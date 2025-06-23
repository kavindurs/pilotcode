<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'business_name',
        'business_email',
        'property_type',
        'first_name',
        'last_name',
        'zip_code',
        'country',
        'annual_revenue',
        'employee_count',
        'category_id',
        'subcategory_id',
        'domain',
        'business_document',
        'status',
        'admin_notes',
        'reviewed_at',
        'reviewed_by'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the property that is being claimed
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the admin who reviewed the claim
     */
    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    /**
     * Get the category of the business claim
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the subcategory of the business claim
     */
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
