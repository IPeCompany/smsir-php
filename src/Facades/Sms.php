<?php


namespace Ipe\Sdk\Facades;

use Illuminate\Support\Facades\Facade;

class Sms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Ipe\Sdk\SmsIr';
    }
}
