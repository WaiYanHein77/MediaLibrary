<?php

namespace App\Contract;

/**
 * Defines methods for user data access.
 */
interface UserRepositoryInterface
extends BaseRepositoryInterface
{
    /*
     * Create user
     */
    public function createUser(
        string $username,
        string $email,
        string $password
    ): bool;

    /*
     * Find user by email
     */
    public function findByEmail(
        string $email
    );
}