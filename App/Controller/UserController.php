<?php

namespace App\Controller;

use App\Service\UserService;
use App\Request\RegisterRequest;
use App\Request\LoginRequest;

class UserController
{
    public function __construct(
        private UserService $userService
    ) {}

    public function register(
        RegisterRequest $request
    ) {

        $errors = [];

        if (
            $_SERVER['REQUEST_METHOD']
            === 'POST'
        ) {
//  echo "<pre>";
//         var_dump($_POST);
//         echo "</pre>";

//         $isValid = $request->validate($_POST);

//         echo "<pre>";
//         var_dump($isValid);
//         echo "</pre>";

//         echo "<pre>";
//         var_dump($request->errors());
//         echo "</pre>";

//         exit; // stop execution for debugging
            if (
                !$request->validate($_POST)
            ) {

                $errors =
                    $request->errors();

            } else {

                $result =
                    $this->userService
                    ->register($_POST);

                if (
                    $result['success']
                ) {

                    header(
                        "Location:index.php?page=login"
                    );

                    exit;
                }

                $errors =
                    $result['errors']
                    ?? [];
            }
        }

        require BASE_PATH
            . '/view/user/register.php';
    }

    public function login(
        LoginRequest $request
    ) {

        $errors = [];
        $old = [];

        if (
            $_SERVER['REQUEST_METHOD']
            === 'POST'
        ) {

            $old = $_POST;

            if (
                !$request->validate($_POST)
            ) {

                $errors =
                    $request->errors();

            } else {

                $result =
                    $this->userService
                    ->login($_POST);

                if (
                    $result['success']
                ) {

                    $_SESSION['user'] =
                        $result['user'];

                    header(
                        "Location:index.php?page=home"
                    );

                    exit;
                }

                $errors =
                    $result['errors']
                    ?? [];
            }
        }

        require BASE_PATH
            . '/view/user/login.php';
    }

    public function logout()
    {
        $_SESSION = [];

        session_destroy();

        header(
            "Location:index.php?page=login"
        );

        exit;
    }
}