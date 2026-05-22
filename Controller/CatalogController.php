<?php

require_once BASE_PATH . '/Controller/BaseController.php';
require_once BASE_PATH . '/Service/CatalogService.php';

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