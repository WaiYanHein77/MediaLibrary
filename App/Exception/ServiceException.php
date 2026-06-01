<?php

namespace App\Exception;

class ServiceException extends BaseException
{
    public function __construct(string $message = "Service error")
    {
        parent::__construct($message, 500);
    }
}