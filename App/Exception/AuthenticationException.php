<?php

namespace App\Exception;

class AuthenticationException extends BaseException
{
    public function __construct(string $message = "Unauthorized")
    {
        parent::__construct($message, 401);
    }
}