<?php

use App\Models\Setting;

if (!function_exists('getSettings')) {
    function getSettings()
    {
        // Fetch all settings as key-value pairs
        $settings = Setting::pluck('value', 'key')->toArray();

        // Convert 'on' to true and anything else (including 'off' or null) to false
        foreach ($settings as $key => $value) {
            $settings[$key] = strtolower($value ?? '') === 'on';
        }

        return $settings;
    }
}
