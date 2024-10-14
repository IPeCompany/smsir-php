<?php


namespace Ipe\Sdk\Facades;

use Illuminate\Support\Facades\Facade;

class SmsIr extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Ipe\Sdk\SmsIrService';
    }
}
