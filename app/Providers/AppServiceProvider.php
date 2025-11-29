<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {

            // Trust Railway Reverse Proxy
            $request = request();
            $request->setTrustedProxies(
                [$request->getClientIp(), '*'],
                Request::HEADER_X_FORWARDED_ALL
            );

            // Force HTTPS
            URL::forceScheme('https');
        }
    }
}
