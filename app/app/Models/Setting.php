<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    protected $fillable = ['key','value'];
    protected $casts = [
        'value' => 'array',
    ];

    public static function get(string $key, $default = null)
    {
        try {
            return optional(static::find($key))->value ?? $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function put(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
