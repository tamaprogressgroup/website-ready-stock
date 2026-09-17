<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'app_m_settings';
    protected $primaryKey = 'settings_id';

    protected $fillable = [
        'settings_key',
        'settings_value',
        'settings_label',
    ];

    public static function isActive(string $key): bool
    {
        $value = static::where('settings_key', $key)->value('settings_value');

        return $value === null || $value === 'active';
    }
}
