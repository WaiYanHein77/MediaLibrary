<?php

namespace App\Service;

class UserService
{
    // public function register(
    //     array $data
    // ): array {

    //     $username =
    //         trim(
    //             $data['username']
    //         );

    //     $email =
    //         trim(
    //             $data['email']
    //         );

    //     $password =
    //         password_hash(
    //             $data['password'],
    //             PASSWORD_DEFAULT
    //         );

    //     unset($data['confirm_password']);

    //     // save repository later

    //     return [
    //         'success' => true
    //     ];
    // }

    public function register(
    array $data
): array {

    $username = trim($data['username']);

    $email = trim($data['email']);

    $password = password_hash(
        $data['password'],
        PASSWORD_DEFAULT
    );

    // ADD THIS
    $_SESSION['registered_user'] = [
        'username' => $username,
        'email' => $email,
        'password' => $password
    ];
    return [
        'success' => true
    ];
}

    // public function login(
    //     array $data
    // ): array {

    //     $email =
    //         trim(
    //             $data['email']
    //         );

    //     $password =
    //         $data['password'];

    //     // repository search later

    //     $user = [
    //         'email' => $email
    //     ];

    //     return [

    //         'success' => true,

    //         'user' => $user

    //     ];
    // }


    public function login(
    array $data
): array {

    $email = trim($data['email']);
    $password = $data['password'];

    // temporary example
    $user = [
        'username' => $_SESSION['registered_user']['username'] ?? '',
        'email' => $email
    ];

    return [

        'success' => true,

        'user' => $user

    ];
}
}
