<?php

namespace App\Contract;

/**
 * Defines methods for user data access.
 */
interface UserRepositoryInterface extends BaseRepositoryInterface 
{
    public function createUser(string $username, string $email, string $password): bool;

    public function findByEmail(string $email);

    public function findById(int $id);
}