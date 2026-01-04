<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Mahasiswa;
use App\Observers\MahasiswaObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

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
        Mahasiswa::observe(MahasiswaObserver::class);

        if (Schema::hasTable('settings')) {
            $site_settings = Setting::all()->pluck('value', 'key');
            View::share('site_settings', $site_settings);

            // Dynamically update config based on settings
            if (isset($site_settings['site_title'])) {
                config(['app.name' => $site_settings['site_title']]);
            }
        }
    }
}
