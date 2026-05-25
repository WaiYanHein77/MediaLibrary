<?php
namespace App\Controller;

use App\Service\UserService;

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // REGISTER
    public function register()
    {
        // session_start();

        if (isset($_SESSION['user'])) {
            header("Location: index.php?page=home");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $result = $this->userService->register($username, $email, $password);

            if ($result) {
                header("Location: index.php?page=login");
                exit;
            } else {
                echo "Email already exists or registration failed";
            }
        }

        require BASE_PATH . '/view/user/register.php';
    }

    // LOGIN
    public function login()
    {
        // session_start();

        if (isset($_SESSION['user'])) {
            header("Location: index.php?page=home");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userService->login($email, $password);

            if ($user) {
                $_SESSION['user'] = $user;

                header("Location: index.php?page=home");
                exit;
            } else {
                echo "Invalid email or password";
            }
        }

        require BASE_PATH . '/view/user/login.php';
    }

    // LOGOUT
   public function logout()
{
    $_SESSION = [];

    session_destroy();

    header("Location: index.php?page=login");

    exit;
}
}