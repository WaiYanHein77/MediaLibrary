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
    // 🔥 TEST MODE: single item
    if (isset($_GET['id'])) {

        $item = $this->catalogService->single_item_array((int) $_GET['id']);

        $this->render('item', [
            'item' => $item,
            'pageTitle' => 'Item Detail'
        ]);

        return;
    }

    // normal catalog page
    $data = $this->catalogService->getCatalogPage($_GET);

    $this->render('catalog', $data);
}
}