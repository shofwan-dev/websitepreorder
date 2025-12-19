<?php

if (!function_exists('logo_url')) {
    /**
     * Get logo URL safely, returns null if logo path is empty
     *
     * @param string|null $logoPath
     * @return string|null
     */
    function logo_url($logoPath)
    {
        if (empty($logoPath) || !is_string($logoPath)) {
            return null;
        }
        
        return asset('storage/' . $logoPath);
    }
}

if (!function_exists('site_logo')) {
    /**
     * Get site logo URL from settings
     *
     * @return string|null
     */
    function site_logo()
    {
        $logo = \App\Models\Setting::getValue('site_logo');
        return logo_url($logo);
    }
}
