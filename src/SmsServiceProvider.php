<?php

namespace MyVendor\SmsPackage;

use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Log the configuration values for debugging
        \Log::info("API Key: " . config('sms.api_key'));
        \Log::info("Base URI: " . config('sms.base_uri'));


        // Register the SmsService class with configuration parameters
        $this->app->singleton(SmsService::class, function ($app) {
            return new SmsService(
                config('sms.api_key'),
                config('sms.base_uri')
            );
        });
    }

    public function boot()
    {
        // Publish the configuration file
        $this->publishes([
            __DIR__.'/../config/sms.php' => config_path('sms.php'),
        ], 'config'); // Adding a tag to the published resources
    }
}
