<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force HTTPs links on production
        if (!$this->app->environment('local')) {
            URL::forceScheme('https');
        }

        Horizon::auth(function () {
            return $this->app->environment('local') || !empty($_SERVER['PHP_AUTH_USER']);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
