<?php

namespace App\Providers;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
    if ($this->app->environment('production')) {

        // Trust all proxies behind Railway
        \Request::setTrustedProxies(
            ['*'],   // <-- only this
            \Illuminate\Http\Request::HEADER_X_FORWARDED_ALL
        );

        // Force HTTPS correctly
        URL::forceScheme('https');
    }
}   
    
}
