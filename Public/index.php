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
use App\Service\SuggestService;
use App\Repository\UserRepository;
use App\Service\Validator;
use App\Request\RegisterRequest;
use App\Request\LoginRequest;
use App\Request\SuggestRequest;
use App\Mapper\UserMapper;
use App\Core\ErrorHandler;

ErrorHandler::register();



/* ======================================================
   ENV
====================================================== */
$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

/* ======================================================
   DB
====================================================== */
$db = Database::getConnection();

/* ======================================================
   SERVICES
====================================================== */
$catalogService = new CatalogService();
$formatService  = new FormatService();
$suggestService = new SuggestService();

$validator = new Validator();
$userMapper = new UserMapper();

$userRepo = new UserRepository($db, $userMapper);
$userService = new UserService($userRepo);

/* ======================================================
   ROUTES
====================================================== */
$webRoutes = require BASE_PATH . '/routes/web.php';
$apiRoutes = require BASE_PATH . '/routes/api.php';

$routes = $webRoutes + $apiRoutes;

$page = $_GET['page'] ?? 'home';

if (!isset($routes[$page])) {

    http_response_code(404);

$message = "Route not found";
require BASE_PATH . '/view/errors/404.php';
exit;
}

[$controllerClass, $method] = $routes[$page];

/* ======================================================
   CONTROLLER FACTORY
====================================================== */
$controller = match ($controllerClass) {

    App\Controller\CatalogController::class,
    App\Controller\Api\ApiCatalogController::class
        => new $controllerClass($catalogService),

    App\Controller\DetailsController::class,
    App\Controller\Api\ApiDetailsController::class
        => new $controllerClass($catalogService),

    App\Controller\UserController::class,
    App\Controller\Api\ApiUserController::class
        => new $controllerClass($userService),

    App\Controller\SuggestController::class,
    App\Controller\Api\ApiSuggestController::class
        => new $controllerClass($formatService, $suggestService),

    default => new $controllerClass()
};

/* ======================================================
   REQUEST FACTORY
====================================================== */
$request = match ($controllerClass . '::' . $method) {

    App\Controller\UserController::class . '::register'
        => new RegisterRequest($validator),

    App\Controller\UserController::class . '::login'
        => new LoginRequest($validator),

    App\Controller\SuggestController::class . '::index'
        => new SuggestRequest($validator),

    default => null
};

/* ======================================================
   EXECUTE CONTROLLER
====================================================== */
if ($request) {
    $controller->$method($request);
} else {
    $controller->$method();
}