<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Daftarkan Livewire components
        Livewire::component('admin.production-manager', \App\Livewire\Admin\ProductionManager::class);
        Livewire::component('admin.production-batches', \App\Livewire\Admin\ProductionBatches::class);
        Livewire::component('admin.production-orders', \App\Livewire\Admin\ProductionOrders::class);
        Livewire::component('admin.production-reports', \App\Livewire\Admin\ProductionReports::class);
    }
}