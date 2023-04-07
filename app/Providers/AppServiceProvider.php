<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

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
    public function boot(UrlGenerator $url)
    {
        if (env("APP_ENV") == "product") {
            $url->forceScheme('https');
        }
        // ownerから始まるURL
        if (request()->is('owner*')) {
            config(['session.cookie' => config('session.cookie_owner')]);
        }

        // adminから始まるURL
        if (request()->is('admin*')) {
            config(['session.cookie' => config('session.cookie_admin')]);
        }
    }
}
