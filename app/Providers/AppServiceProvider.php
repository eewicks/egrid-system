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
        // Force HTTPS for Railway production environment
        if ($this->app->environment('production')) {

            // Force URL generation to HTTPS
            URL::forceScheme('https');

            // Allow Railway proxy headers so HTTPS is detected correctly
            Request::setTrustedProxies(
                [Request::getClientIp()],
                Request::HEADER_X_FORWARDED_ALL
            );
        }
    }
}
