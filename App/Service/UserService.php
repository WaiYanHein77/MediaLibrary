<?php

namespace App\Service;

class UserService
{
    public function register(
        array $data
    ): array {

        $username =
            trim(
                $data['username']
            );

        $email =
            trim(
                $data['email']
            );

        $password =
            password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            );

        // save repository later

        return [
            'success' => true
        ];
    }

    public function login(
        array $data
    ): array {

        $email =
            trim(
                $data['email']
            );

        $password =
            $data['password'];

        // repository search later

        $user = [
            'email' => $email
        ];

        return [

            'success' => true,

            'user' => $user

        ];
    }
}