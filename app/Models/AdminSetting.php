<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description'
    ];

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        // Cast the value based on type
        switch ($setting->type) {
            case 'number':
                return is_numeric($setting->value) ? (float) $setting->value : $default;
            case 'boolean':
                return in_array(strtolower($setting->value), ['true', '1', 'yes', 'on']);
            case 'json':
                return json_decode($setting->value, true) ?: $default;
            default:
                return $setting->value;
        }
    }

    /**
     * Set setting value by key
     */
    public static function set($key, $value, $type = 'string', $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description
            ]
        );
    }

    /**
     * Get the ad daily cost setting
     */
    public static function getAdDailyCost()
    {
        return self::get('ad_daily_cost', 1.00);
    }
}
