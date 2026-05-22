<?php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}
require_once BASE_PATH . '/Contract/CatalogRepositoryInterface.php';
require_once BASE_PATH . '/Service/FormatService.php';
require_once BASE_PATH . '/Contract/FormatRepositoryInterface.php';
require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/inc/Database.php';
require_once BASE_PATH . '/inc/CustomPath.php';
require_once BASE_PATH . '/Controller/api/ApiDetailsController.php';
require_once BASE_PATH . '/Controller/api/ApiCatalogController.php';
require_once BASE_PATH . '/Controller/api/ApiSuggestController.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
/* =========================
   INIT SERVICES
========================= */
$catalogService = new CatalogService();
$formatService  = new FormatService();

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

    // API routes
    'api/catalog' => [ApiCatalogController::class, 'index', $catalogService],
    'api/home' => [ApiCatalogController::class, 'home', $catalogService],
    'api/details' => [ApiDetailsController::class, 'show', $catalogService],
    'api/suggest' => [ApiSuggestController::class, 'submit', $formatService],
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
   404 FALLBACK
========================= */

http_response_code(404);
echo json_encode([
    'success' => false,
    'message' => 'Route not found'
]);
exit;