<?php

namespace App\Controller;

use App\Service\CatalogService;

class CatalogController extends BaseController
{
    public function __construct(
        private CatalogService $catalogService
    ) {}

    public function home(): void
    {
        $this->render('home', [
            'pageTitle' => 'Personal Media Library',
            'section' => 'catalog',
            'random' => $this->catalogService->random_catalog_array()
        ]);
    }

    public function index(): void
    {
        $data = $this->catalogService->getCatalogPage($_GET);
        $this->render('catalog', $data);
    }
}