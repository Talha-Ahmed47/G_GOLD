<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key, with optional fallback.
     */
    public static function getVal(string $key, $default = null): string
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : (string) $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function setVal(string $key, ?string $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
