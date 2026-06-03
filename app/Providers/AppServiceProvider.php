<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Paksa semua asset URL pakai ngrok
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $ngrokUrl = 'https://' . $_SERVER['HTTP_X_FORWARDED_HOST'];
            URL::forceRootUrl($ngrokUrl);
            URL::forceScheme('https');
        }
    }
}
