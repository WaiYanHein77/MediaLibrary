<?php

namespace App\Service;

use App\Exception\ValidationException;
use App\Exception\AuthenticationException;
use App\Mapper\UserResponseMapper;
use App\Repository\UserRepository;
use App\Model\User;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function register($dto)
    {
        // Business rule: email must be unique
        if ($this->userRepository->findByEmail($dto->email)) {
            throw new ValidationException([
                'email' => ['Email already exists']
            ]);
        }

        $user = new User(
            null,
            $dto->username,
            $dto->email,
            password_hash($dto->password, PASSWORD_BCRYPT)
        );

        $this->userRepository->save($user);

        return UserResponseMapper::toDTO($user);
    }

    public function login($dto)
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user || !$user->verifyPassword($dto->password)) {
            throw new AuthenticationException("Invalid credentials");
        }

        return UserResponseMapper::toDTO($user);
    }
}