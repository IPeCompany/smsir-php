<?php


namespace MyVendor\SmsPackage\Facades;

use Illuminate\Support\Facades\Facade;

class Sms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'MyVendor\SmsPackage\SmsService';
    }
}
