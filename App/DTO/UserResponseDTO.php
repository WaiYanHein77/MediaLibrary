<?php

namespace App\DTO;

class UserResponseDTO
{
    public function __construct(
        public ?int $id,
        public string $username,
        public string $email
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email
        ];
    }
}