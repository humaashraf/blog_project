<?php

namespace App\Providers;
use App\Models\Setting;
use Illuminate\Support\Facades\View;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
          // Settings ko har view ke liye share karna
    View::composer('*', function ($view) {
        $settings = Setting::first();
        $view->with('settings', $settings);
    });
    }
}
