<?php

namespace App\Controller;

use App\Service\CatalogService;
use App\Controller\BaseController;

class CatalogController extends BaseController
{
    private CatalogService $catalogService;

    public function __construct(
        CatalogService $catalogService
    ) {
        $this->catalogService = $catalogService;
    }

    public function home(): void
    {
        $this->render(
            'home',
            [
                'pageTitle' => 'Personal Media Library',
                'section' => 'catalog',
                'random' => $this->catalogService
                    ->random_catalog_array()
            ]
        );
    }

    public function index(): void
    {
        $data = $this->catalogService
            ->getCatalogPage($_GET);

        $this->render('catalog', $data);
    }
}