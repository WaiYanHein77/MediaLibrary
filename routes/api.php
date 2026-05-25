<?php

use App\Controller\Api\ApiCatalogController;
use App\Controller\Api\ApiDetailsController;
use App\Controller\Api\ApiSuggestController;
use App\Controller\Api\ApiUserController;

return [
    'api/catalog' => [ApiCatalogController::class, 'index'],
    'api/home'    => [ApiCatalogController::class, 'home'],
    'api/details' => [ApiDetailsController::class, 'show'],
    'api/suggest' => [ApiSuggestController::class, 'submit'],

    'api/login'    => [ApiUserController::class, 'login'],
    'api/register' => [ApiUserController::class, 'register'],
    'api/logout'   => [ApiUserController::class, 'logout'],
];