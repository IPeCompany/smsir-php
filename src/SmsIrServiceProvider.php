<?php

namespace Ipe\Sdk;

use Illuminate\Support\ServiceProvider;

class SmsIrServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SmsIrService::class, function ($app) {
            return new SmsIrService(
                env('SMSIR_API_KEY'),  
                'https://api.sms.ir/v1/'  
            );
        });
    }

    public function boot()
    {
    }
}
