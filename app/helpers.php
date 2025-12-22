<?php

if (!function_exists('site_name')) {
    /**
     * Get the site name from settings.
     *
     * @param string $default
     * @return string
     */
    function site_name($default = 'PO Kaligrafi Lampu')
    {
        return \App\Models\Setting::where('key', 'site_name')->value('value') ?? $default;
    }
}

if (!function_exists('site_setting')) {
    /**
     * Get a site setting value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function site_setting($key, $default = null)
    {
        return \App\Models\Setting::where('key', $key)->value('value') ?? $default;
    }
}
