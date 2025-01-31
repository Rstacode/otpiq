<?php
namespace Rstacode\Otpiq;

use Illuminate\Support\ServiceProvider;

class OtpiqServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/otpiq.php', 'otpiq');

        $this->app->singleton(OtpiqService::class, function ($app) {
            return new OtpiqService(config('otpiq.api_key'), config('otpiq.base_url'));
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/otpiq.php' => config_path('otpiq.php'),
        ], 'otpiq-config');
    }
}
