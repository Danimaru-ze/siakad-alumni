<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;

class ViewServiceProvider extends ServiceProvider
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
        // Share settings globally to all views
        view()->composer('*', function ($view) {
            $settings = Setting::all()->pluck('value', 'key');
            $view->with([
                'siteTitle' => $settings['sidebar_title'] ?? config('app.name'),
                'websiteSettings' => $settings,
            ]);
        });
    }
}
