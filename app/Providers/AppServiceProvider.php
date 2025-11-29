<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

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

            // Force HTTPS on all generated URLs
            URL::forceScheme('https');

            // TRUST RAILWAY PROXY HEADERS
            Request::setTrustedProxies(
                [Request::HEADER_X_FORWARDED_FOR],
                Request::HEADER_X_FORWARDED_ALL
            );
        }
    }
}
