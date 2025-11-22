<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = 'string', ?string $description = null): void
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
            ]
        );

        // Clear cache
        Cache::forget("setting_{$key}");
    }

    /**
     * Cast value based on type
     */
    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'boolean' => (bool) $value,
            'number' => is_numeric($value) ? (strpos($value, '.') !== false ? (float) $value : (int) $value) : $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Get conversion rate
     */
    public static function getConversionRate(): float
    {
        return (float) self::get('conversion_rate', 0.001);
    }

    /**
     * Get minimum conversion pieces
     */
    public static function getMinimumConversionPieces(): int
    {
        return (int) self::get('minimum_conversion_pieces', 10000);
    }

    /**
     * Check if conversion is enabled
     */
    public static function isConversionEnabled(): bool
    {
        return (bool) self::get('conversion_enabled', true);
    }
}
