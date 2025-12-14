<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    // Get setting value by key
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting. {$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            return match($setting->type) {
                'boolean' => (bool) $setting->value,
                'integer' => (int) $setting->value,
                'json' => json_decode($setting->value, true),
                default => $setting->value,
            };
        });
    }

    // Set setting value
    public static function set(string $key, $value, string $type = 'string'): void
    {
        $storeValue = $type === 'json' ? json_encode($value) : (string) $value;

        self::updateOrCreate(
            ['key' => $key],
            ['value' => $storeValue, 'type' => $type]
        );

        Cache::forget("setting.{$key}");
    }
}