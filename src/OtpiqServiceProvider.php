<?php

namespace Rstacode\Otpiq;

use Illuminate\Support\ServiceProvider;

class OtpiqServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/otpiq.php', 'otpiq');
        
        $this->app->singleton('otpiq', function ($app) {
            return new Otpiq($app['config']['otpiq']);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/otpiq.php' => config_path('otpiq.php'),
        ], 'config');
    }
}