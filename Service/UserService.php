<?php
namespace Service;

use Contract\UserRepositoryInterface;
use Repository\UserRepository;
use Inc\Database as IncDatabase;
class UserService
{
    private UserRepositoryInterface $userRepo;

    // public function __construct(UserRepositoryInterface $userRepo)
    // {
    //     $this->userRepo = $userRepo;
    // }
    public function __construct(
        UserRepositoryInterface $userRepo
    ) {
        if ($userRepo === null) {
            $db = IncDatabase::getConnection();

            $userRepo = new UserRepository($db);
        }

         $this->userRepo = $userRepo;
    }

    public function register(string $username, string $email, string $password): bool
    {
        // check if email exists
        if ($this->userRepo->findByEmail($email)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        return $this->userRepo->createUser($username, $email, $hashedPassword);
    }

    public function login(string $email, string $password)
    {
        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        return $user;
    }
}