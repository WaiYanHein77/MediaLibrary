<?php

namespace App\Mapper;

use App\Model\User;
use App\DTO\UserResponseDTO;

class UserResponseMapper
{
    public static function toDTO(User $user): UserResponseDTO
    {
        return new UserResponseDTO(
            $user->getId(),
            $user->getUsername(),
            $user->getEmail()
        );
    }
}