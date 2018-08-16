<?php

namespace App\Providers;

use App\OAuth\Ynab;
use Illuminate\Support\ServiceProvider;

class OAuthYnabServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Ynab::class, function () {
            return new Ynab([
                'clientId'          => env('YNAB_CLIENT_ID'),
                'clientSecret'      => env('YNAB_CLIENT_SECRET'),
                'redirectUri'       => url('/ynab/redirect'),
            ]);
        });

    }
}
