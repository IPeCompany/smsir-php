<?php

namespace MyVendor\SmsPackage\Exceptions;

use Exception;

class SmsException extends Exception
{
    protected $statusCode;

    // Constructor accepts a message and status code
    public function __construct($message = "An unexpected error occurred", $statusCode = null)
    {
        $this->statusCode = $statusCode; // Set the status code
        parent::__construct($message); // Call the parent constructor
    }

    // Getter for the status code
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
