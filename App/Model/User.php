<?php

// namespace App\Model;

// class User
// {
//     public function __construct(
//         public ?int $id = null,
//         public string $username = '',
//         public string $email = '',
//         public string $password = ''
//     ) {}
// }

namespace App\Model;

class User
{
    private ?int $id;
    private string $username;
    private string $email;
    private string $password;

    public function __construct(
        ?int $id = null,
        string $username = '',
        string $email = '',
        string $password = ''
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    /*
     Get ID
    */
    public function getId(): ?int
    {
        return $this->id;
    }

    /*
     Set ID
    */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /*
     Get Username
    */
    public function getUsername(): string
    {
        return $this->username;
    }

    /*
     Set Username
    */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /*
     Get Email
    */
    public function getEmail(): string
    {
        return $this->email;
    }

    /*
     Set Email
    */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /*
     Get Password
    */
    public function getPassword(): string
    {
        return $this->password;
    }

    /*
     Set Password
    */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}