<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share settings with all views
        View::composer('*', function ($view) {
            // Check if settings table exists to prevent errors during migration
            if (!Schema::hasTable('settings')) {
                $view->with('site_settings', []);
                return;
            }
            
            try {
                $siteSettings = Setting::getGroup('website');
                $view->with('site_settings', $siteSettings);
            } catch (\Exception $e) {
                $view->with('site_settings', []);
            }
        });
    }
}
