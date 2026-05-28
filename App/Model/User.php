<?php

namespace App\Model;

class User
{
    private ?int $id;
    private string $username;
    private string $email;
    private string $passwordHash;

    public function __construct(
        ?int $id,
        string $username,
        string $email,
        string $passwordHash
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function getId(): ?int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    // SAFE OUTPUT ONLY
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email
        ];
    }
}