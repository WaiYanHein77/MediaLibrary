<?php

namespace App\Repository;

use App\Model\User;
use App\Mapper\UserMapper;
use PDO;

class UserRepository extends BaseRepository
{
    protected string $table = 'users';

    public function __construct(
        PDO $db,
        private UserMapper $mapper
    ) {
        parent::__construct($db);
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users
            WHERE email = :email
            LIMIT 1
        ");

        $stmt->execute([':email' => $email]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->mapper->toEntity($data) : null;
    }

    public function save(User $user): bool
    {
        if ($user->getId() === null) {
            return $this->insert($user);
        }

        return $this->update($user);
    }

    private function insert(User $user): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password)
            VALUES (:username, :email, :password)
        ");

        return $stmt->execute([
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail(),
            ':password' => $user->getPasswordHash()
        ]);
    }

    private function update(User $user): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET username = :username,
                email = :email
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $user->getId(),
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail()
        ]);
    }
}