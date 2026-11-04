<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type', // text, number, boolean, json
    ];

    protected $casts = [
        'value' => 'string',
    ];

    // Helpers
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return match($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'number' => (float) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    public static function set(string $key, $value, string $type = 'text'): void
    {
        if ($type === 'json') {
            $value = json_encode($value);
        } elseif ($type === 'boolean') {
            $value = $value ? '1' : '0';
        }

        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }

    // Common settings keys
    public static function getBankName(): string
    {
        return self::get('bank_name', 'BCA');
    }

    public static function getBankAccountNumber(): string
    {
        return self::get('bank_account_number', '');
    }

    public static function getBankAccountName(): string
    {
        return self::get('bank_account_name', 'KNOTTED');
    }

    public static function getStoreEmail(): string
    {
        return self::get('store_email', 'hello@knotted.com');
    }

    public static function getStorePhone(): string
    {
        return self::get('store_phone', '');
    }

    public static function getStoreAddress(): string
    {
        return self::get('store_address', '');
    }
}
