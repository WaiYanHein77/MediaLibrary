<?php

namespace App\Exception;

use Exception;

abstract class BaseException extends Exception
{
    public function __construct(
        string $message = "",
        int $code = 0,
        protected array $context = []
    ) {
        parent::__construct($message, $code);
    }

    public function context(): array
    {
        return $this->context;
    }
}