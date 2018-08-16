<?php

namespace App\Providers;

use App\OAuth\Monzo;
use Illuminate\Support\ServiceProvider;

class OAuthMonzoServiceProvider extends ServiceProvider
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
        $this->app->singleton(Monzo::class, function () {
            return new Monzo([
                'clientId'          => env('MONZO_CLIENT_ID'),
                'clientSecret'      => env('MONZO_CLIENT_SECRET'),
                'redirectUri'       => 'https://monzo-to-ynab.ashleyhindle.com/monzo/redirect',//url('/monzo/redirect'),
            ]);
        });

    }
}
