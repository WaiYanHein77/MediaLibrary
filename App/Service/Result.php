<?php

namespace App\Service;

use App\Model\User;

class Result
{
    public function __construct(
        private bool $success,
        private array $errors = [],
        private mixed $data = null
    ) {}

    public function isSuccess(): bool { return $this->success; }
    public function errors(): array { return $this->errors; }
    public function data(): mixed { return $this->data; }

    public function user(): ?User
    {
        return $this->data instanceof User ? $this->data : null;
    }
}