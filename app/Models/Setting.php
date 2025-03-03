<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
    protected $fillable = ['key', 'value'];

    // Get setting value by key
    public static function getValue($key) {
        return self::where('key', $key)->value('value') ?? 'off';
    }

    // Update setting value
    public static function setValue($key, $value) {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
