<?php

namespace Ipe\Sdk\Exceptions;

use Exception;

class SmsException extends Exception
{
    protected $statusCode;

    public function __construct($message = "An unexpected error occurred", $statusCode = null)
    {
        $this->statusCode = $statusCode; 
        parent::__construct($message); 
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
