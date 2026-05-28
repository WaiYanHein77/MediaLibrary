<?php

namespace App\Mapper;

use App\Model\User;

class UserMapper
{
    public function toEntity(array $data): User
    {
        return new User(
            $data['id'] ?? null,
            $data['username'],
            $data['email'],
            $data['password'] // DB column
        );
    }
}