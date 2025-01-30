<?php
namespace Rstacode\Otpiq;

use Illuminate\Support\ServiceProvider;
use Rstacode\Otpiq\Services\OtpiqService;

class OtpiqServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register config
        $this->mergeConfigFrom(
            __DIR__ . '/Config/otpiq.php', 'otpiq'
        );

        // Register the main service
        $this->app->singleton(OtpiqService::class, function ($app) {
            return new OtpiqService();
        });

        // Register the facade accessor
        $this->app->bind('otpiq', function ($app) {
            return $app->make(OtpiqService::class);
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Config/otpiq.php' => config_path('otpiq.php'),
            ], 'otpiq-config');
        }
    }
}
