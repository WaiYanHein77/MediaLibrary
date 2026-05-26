<?php

namespace App\Service;

use App\Contract\UserRepositoryInterface;
use App\Repository\UserRepository;
use App\Inc\Database as IncDatabase;
use App\Model\User;
use App\Service\Validator;

class UserService
{
    private UserRepositoryInterface $userRepo;

    private Validator $validator;

    public function __construct(
        UserRepositoryInterface $userRepo,
        Validator $validator
    ) {
        if ($userRepo === null) {
            $db = IncDatabase::getConnection();

            $userRepo = new UserRepository($db);
        }

        $this->userRepo = $userRepo;
        $this->validator = $validator;
    }

    // public function register(string $username, string $email, string $password): bool
    // {
    //     // check if email exists
    //     if ($this->userRepo->findByEmail($email)) {
    //         return false;
    //     }

    //     $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    //     return $this->userRepo->createUser($username, $email, $hashedPassword);
    // }
    public function register(array $data): array
    {

        /*
         dynamic validation
        */

        if (
            !$this->validator
                ->validate(
                    $data,
                    User::rules()
                )
        ) {

            return [

                'success' => false,

                'errors' =>
                $this->validator
                    ->errors()

            ];
        }


        /*
         email exists
        */

        if (
            $this->userRepo
            ->findByEmail(
                $data['email']
            )
        ) {

            return [

                'success' => false,

                'errors' => [
                    'email' => [
                        'Email already exists'
                    ]
                ]
            ];
        }


        $hashedPassword =
            password_hash(
                $data['password'],
                PASSWORD_BCRYPT
            );


        $created =
            $this->userRepo
            ->createUser(

                $data['username'],

                $data['email'],

                $hashedPassword
            );


        return [

            'success' => $created

        ];
    }



    // public function login(string $email, string $password)
    // {
    //     $user = $this->userRepo->findByEmail($email);

    //     if (!$user) {
    //         return false;
    //     }

    //     if (!password_verify($password, $user['password'])) {
    //         return false;
    //     }

    //     return $user;
    // }
    public function login(array $data)
{
    /*
     validate login
    */
    if (
        !$this->validator->validate(
            $data,
            User::loginRules()
        )
    ) {
        return [
            'success' => false,
            'errors' => $this->validator->errors()
        ];
    }

    /*
     find user
    */
    $user = $this->userRepo->findByEmail($data['email']);

    if (!$user) {
        return [
            'success' => false,
            'errors' => [
                'email' => ['Email not found']
            ]
        ];
    }

    /*
     password check
    */
    if (!password_verify($data['password'], $user['password'])) {
        return [
            'success' => false,
            'errors' => [
                'password' => ['Incorrect password']
            ]
        ];
    }

    return [
        'success' => true,
        'user' => $user
    ];
}
}
