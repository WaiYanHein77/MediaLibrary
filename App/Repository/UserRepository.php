<?php

namespace App\Repository;

use App\Contract\UserRepositoryInterface;

use PDO;

class UserRepository extends BaseRepository
implements UserRepositoryInterface
{
    /*
     * TABLE CONFIGURATION
     */
    protected string $table = 'users';

    protected string $primaryKey = 'id';


    /*
     * CREATE USER
     */
    public function createUser(
        string $username,
        string $email,
        string $password
    ): bool {

        $sql = "
            INSERT INTO users (
                username,
                email,
                password
            )
            VALUES (
                :username,
                :email,
                :password
            )
        ";

        $stmt = $this->db->prepare($sql);

        $success = $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password
        ]);

        $stmt->closeCursor();

        return $success;
    }


    /*
     * FIND BY EMAIL
     */
    public function findByEmail(
        string $email
    ) {

        $stmt = $this->db->prepare(
            "
            SELECT *
            FROM {$this->table}
            WHERE email = :email
            LIMIT 1
            "
        );

        $stmt->execute([
            ':email' => $email
        ]);

        $user = $stmt->fetch(
            PDO::FETCH_ASSOC
        );

        $stmt->closeCursor();

        return $user ?: null;
    }

    /*
     * getById()
     * inherited from BaseRepository
     *
     * getAll()
     * inherited from BaseRepository
     *
     * count()
     * inherited from BaseRepository
     */
}