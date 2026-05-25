<?php
session_start();
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}
require_once BASE_PATH . '/vendor/autoload.php';

use Dotenv\Dotenv;

use Service\CatalogService;
use Service\FormatService;
use Service\UserService;

use Controller\CatalogController;
use Controller\DetailsController;
use Controller\SuggestController;
use Controller\UserController;

use Controller\Api\ApiCatalogController;
use Controller\Api\ApiDetailsController;
use Controller\Api\ApiSuggestController;
use Controller\Api\ApiUserController;

use Inc\Database;
use Repository\UserRepository;


$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// STEP 1: DB CONNECTION
$db = Database::getConnection();

// STEP 2: CREATE OBJECTS IN CORRECT ORDER

/* Catalog */
$catalogService = new CatalogService();
$formatService  = new FormatService();

$userrepo=new UserRepository($db);
$userService=new UserService($userrepo);

/* =========================
   GET ROUTE
========================= */
$page = $_GET['page'] ?? 'home';

/* =========================
   ROUTE MAP (CLEAN & DYNAMIC)
========================= */

$routes = [

    // MVC routes
    'home' => [CatalogController::class, 'home', $catalogService],
    'catalog' => [CatalogController::class, 'index', $catalogService],
    'details' => [DetailsController::class, 'show', $catalogService],
    'suggest' => [SuggestController::class, 'index', $formatService],

      // USER AUTH
    'login' => [UserController::class, 'login', $userService],
    'register' => [UserController::class, 'register', $userService],
    'logout' => [UserController::class, 'logout', $userService],

    // API routes
    'api/catalog' => [ApiCatalogController::class, 'index', $catalogService],
    'api/home' => [ApiCatalogController::class, 'home', $catalogService],
    'api/details' => [ApiDetailsController::class, 'show', $catalogService],
    'api/suggest' => [ApiSuggestController::class, 'submit', $formatService],

     // USER API
    'api/login' => [ApiUserController::class, 'login', $userService],
    'api/register' => [ApiUserController::class, 'register', $userService],
    'api/logout' => [ApiUserController::class, 'logout', $userService],

];

/* =========================
   EXECUTE ROUTE
========================= */

if (isset($routes[$page])) {

    [$controllerClass, $method, $service] = $routes[$page];

    $controller = new $controllerClass($service);

    $controller->$method();

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

exit;