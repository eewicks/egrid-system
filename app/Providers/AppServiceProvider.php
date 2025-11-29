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
    public function boot(Request $request): void
    {
        if ($this->app->environment('production')) {

            // TRUST ALL PROXIES (Railway Load Balancer)
            $request->setTrustedProxies(
                [ $request->getClientIp() ],   // SAFE fallback for Railway
                Request::HEADER_X_FORWARDED_ALL
            );

            // FORCE HTTPS
            URL::forceScheme('https');
        }
    }
}
