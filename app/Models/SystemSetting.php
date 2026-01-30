<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

/**
 * SystemSetting Model
 *
 * Manages system-wide configuration settings with caching,
 * encryption for sensitive data, and type casting.
 */
class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public',
        'is_encrypted',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'is_encrypted' => 'boolean',
        ];
    }

    /**
     * Get a setting value with type casting and caching.
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = "system_setting_{$key}";

        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            $value = $setting->is_encrypted
                ? Crypt::decryptString($setting->value)
                : $setting->value;

            return static::castValue($value, $setting->type);
        });
    }

    /**
     * Set a setting value with encryption if needed.
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general', bool $isEncrypted = false): bool
    {
        $processedValue = $isEncrypted ? Crypt::encryptString($value) : $value;

        if (in_array($type, ['array', 'json']) && is_array($value)) {
            $processedValue = json_encode($value);
        }

        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $processedValue,
                'type' => $type,
                'group' => $group,
                'is_encrypted' => $isEncrypted,
            ]
        );

        // Clear cache
        Cache::forget("system_setting_{$key}");

        return true;
    }

    /**
     * Cast value to appropriate type.
     */
    protected static function castValue($value, string $type)
    {
        return match($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            'array', 'json' => json_decode($value, true) ?? [],
            default => $value,
        };
    }

    /**
     * Get all settings by group.
     */
    public static function getByGroup(string $group): array
    {
        $cacheKey = "system_settings_group_{$group}";

        return Cache::remember($cacheKey, 3600, function () use ($group) {
            $settings = static::where('group', $group)->get();

            $result = [];
            foreach ($settings as $setting) {
                $value = $setting->is_encrypted
                    ? Crypt::decryptString($setting->value)
                    : $setting->value;

                $result[$setting->key] = static::castValue($value, $setting->type);
            }

            return $result;
        });
    }

    /**
     * Clear all settings cache.
     */
    public static function clearCache(): void
    {
        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget("system_setting_{$setting->key}");
        }

        $groups = static::distinct('group')->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("system_settings_group_{$group}");
        }
    }

    /**
     * Boot method to clear cache on model events.
     */
    protected static function booted(): void
    {
        static::saved(function ($setting) {
            Cache::forget("system_setting_{$setting->key}");
            Cache::forget("system_settings_group_{$setting->group}");
        });

        static::deleted(function ($setting) {
            Cache::forget("system_setting_{$setting->key}");
            Cache::forget("system_settings_group_{$setting->group}");
        });
    }
}
