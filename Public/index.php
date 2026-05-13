
<?php
/**
 * Main application entry point.
 * Initializes dependencies, services, and application routing.
 */
/*
//Report simple running errors
error_reporting(E_ALL);
//Make sure they are on screen
ini_set('display_errors',1);
//HTML formatted errors
ini_set('html_errors',1);
        OR
use @ in front of error
*/
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/inc/Database.php';
require_once BASE_PATH . '/inc/CustomPath.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

/*BUILD SHARED OBJECTS*/

$db = Database::getConnection();

/* Repositories */
$catalogRepo = new CatalogRepository($db);
$formatRepo  = new FormatRepository($db);

/* Services */
$catalogService = new CatalogService($catalogRepo);
$formatService  = new FormatService($formatRepo);

/*ROUTING */

$page = $_GET['page'] ?? 'home';

switch ($page) {

    case 'details':
        $controller = new DetailsController($catalogService);
        $controller->show();
        break;

    case 'suggest':
        $controller = new SuggestController($formatService);
        $controller->index();
        break;

    case 'catalog':
        $controller = new CatalogController($catalogService);
        $controller->index();
        break;

    default: // HOME PAGE
        $controller = new CatalogController($catalogService);
        $controller->home();
}

