<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
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
        Vite::prefetch(concurrency: 3);
        if ($this->app->environment('production')) {
            // 1) Fuerza esquema HTTPS (para cualquier helper route(), asset(), url(), etc.)
            URL::forceScheme('https');
            // 2) Asegura que la raíz coincida con APP_URL (útil si estás detrás de proxy)
            URL::forceRootUrl(config('app.url'));
        }
    }
}
