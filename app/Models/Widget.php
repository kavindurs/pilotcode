<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property_id',
        'widget_type',
        'title',
        'content',
        'image_path',
        'link_url',
        'settings',
        'order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'json',
        'is_active' => 'boolean',
    ];

    /**
     * Get the property that owns the widget.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
