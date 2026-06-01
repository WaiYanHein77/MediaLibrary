<?php

namespace App\Exception;

class ValidationException extends BaseException
{
    public function __construct(array $errors = [])
    {
        parent::__construct("Validation failed", 422, $errors);
    }
}