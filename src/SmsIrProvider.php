<?php

namespace Ipe\Sdk;

use Illuminate\Support\ServiceProvider;

class SmsIrProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SmsIr::class, function ($app) {
            return new SmsIr(
                env('SMSIR_API_KEY'),  
                'https://api.sms.ir/v1/'  
            );
        });
    }

    public function boot()
    {
    }
}
