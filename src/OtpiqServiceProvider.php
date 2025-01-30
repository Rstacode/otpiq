<?php
/**
 * Otpiq Service Provider for Laravel
 * دابینکەری خزمەتگوزاری Otpiq بۆ Laravel
 *
 * @package Rstacode\Otpiq
 */
namespace Rstacode\Otpiq;

use Illuminate\Support\ServiceProvider;

class OtpiqServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider
     * تۆمارکردنی دابینکەری خزمەتگوزاری
     *
     * Merges package configuration and binds Otpiq instance to service container
     * تێکەڵکردنی ڕێکخستنەکان و بەستنەوەی نموونەی Otpiq بە service container
     *
     * @return void
     */
    public function register()
    {
        // Merge package config with application config
        // تێکەڵکردنی ڕێکخستنەکانی پاکێج لەگەڵ ڕێکخستنەکانی ئەپڵیکەیشن
        $this->mergeConfigFrom(__DIR__ . '/../config/otpiq.php', 'otpiq');

        // Register Otpiq singleton
        // تۆمارکردنی Otpiq وەک singleton
        $this->app->singleton('otpiq', function ($app) {
            return new Otpiq($app['config']['otpiq']);
        });
    }

    /**
     * Bootstrap any package services
     * دەستپێکردنی خزمەتگوزاریەکانی پاکێج
     *
     * Publishes package configuration
     * بڵاوکردنەوەی ڕێکخستنەکانی پاکێج
     *
     * @return void
     */
    public function boot()
    {
        // Publish package config
        // بڵاوکردنەوەی ڕێکخستنەکان
        $this->publishes([
            __DIR__ . '/../config/otpiq.php' => config_path('otpiq.php'),
        ], 'config');
    }
}
