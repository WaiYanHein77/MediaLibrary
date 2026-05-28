<?php

session_start();

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/view/ItemView.php';
require_once BASE_PATH . '/App/Helper/session_helper.php';

use Dotenv\Dotenv;
use App\Inc\Database;
use App\Service\CatalogService;
use App\Service\FormatService;
use App\Service\UserService;
use App\Repository\UserRepository;
use App\Service\Validator;
use App\Request\RegisterRequest;
use App\Request\LoginRequest;
use App\Mapper\UserMapper;
/* =========================
   ENV
========================= */

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

/* =========================
   DB
========================= */

$db = Database::getConnection();

/* =========================
   SERVICES
========================= */

$catalogService = new CatalogService();
$formatService  = new FormatService();

$validator = new Validator();
$userMapper = new UserMapper(); // ✅ ADD THIS

$userRepo = new UserRepository($db, $userMapper); // ✅ FIXED
$userService = new UserService($userRepo, $validator);
/* =========================
   LOAD ROUTES
========================= */

$webRoutes = require BASE_PATH . '/routes/web.php';
$apiRoutes = require BASE_PATH . '/routes/api.php';

$routes = $webRoutes + $apiRoutes;

/* =========================
   DISPATCH
========================= */

$page = $_GET['page'] ?? 'home';


if (isset($routes[$page])) {

    [$controllerClass, $method] = $routes[$page];

    // inject correct service automatically
    $service = match ($controllerClass) {
        App\Controller\CatalogController::class,
        App\Controller\Api\ApiCatalogController::class
        => $catalogService,

        App\Controller\DetailsController::class,
        App\Controller\Api\ApiDetailsController::class
        => $catalogService,

        App\Controller\SuggestController::class,
        App\Controller\Api\ApiSuggestController::class
        => $formatService,

        App\Controller\UserController::class,
        App\Controller\Api\ApiUserController::class
        => $userService,

        default => null
    };

    $controller = $service
        ? new $controllerClass($service)
        : new $controllerClass();

    $request = null;

    if (
        $controllerClass === App\Controller\UserController::class
    ) {

        $request = match ($method) {

            'register' =>
            new RegisterRequest($validator),

            'login' =>
            new LoginRequest($validator),

            default => null
        };
    }

    if ($request) {

        $controller->$method(
            $request
        );
    } else {

        $controller->$method();
    }

    exit;
}

/* =========================
   404
========================= */

http_response_code(404);

echo json_encode([
    'success' => false,
    'message' => 'Route not found'
]);
