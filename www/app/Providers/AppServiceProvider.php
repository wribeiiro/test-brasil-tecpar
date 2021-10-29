<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
     * Configure global rate limiter
     *
     * @return void
     */
    public function boot()
    {
        app(\Illuminate\Cache\RateLimiter::class)->for('global', function () {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(10)->by(request()->ip());
        });
    }
}
