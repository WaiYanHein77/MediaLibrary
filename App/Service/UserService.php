<?php

namespace App\Service;

use App\Mapper\UserResponseMapper;
use App\Repository\UserRepository;
use App\Model\User;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function register($dto): Result
    {
        if ($this->userRepository->findByEmail($dto->email)) {
            return new Result(false, [
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

        $responseDTO = UserResponseMapper::toDTO($user);

        return new Result(
            true,
            [],
            $responseDTO
        );
    }

    public function login($dto): Result
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user || !$user->verifyPassword($dto->password)) {
            return new Result(false, [
                'login' => ['Invalid credentials']
            ]);
        }

        $responseDTO = UserResponseMapper::toDTO($user);

        return new Result(
            true,
            [],
            $responseDTO
        );
    }
}
