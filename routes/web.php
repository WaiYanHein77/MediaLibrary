<?php

use App\Controller\CatalogController;
use App\Controller\DetailsController;
use App\Controller\SuggestController;
use App\Controller\UserController;

return [
    'home'    => [CatalogController::class, 'home'],
    'catalog' => [CatalogController::class, 'index'],
    'details' => [DetailsController::class, 'show'],
    'suggest' => [SuggestController::class, 'index'],

    'login'    => [UserController::class, 'login'],
    'register' => [UserController::class, 'register'],
    'logout'   => [UserController::class, 'logout'],
];