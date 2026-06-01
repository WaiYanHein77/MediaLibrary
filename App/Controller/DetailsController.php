<?php

namespace App\Controller;

use App\Service\CatalogService;

class DetailsController extends BaseController
{
    public function __construct(
        private CatalogService $catalogService
    ) {}

   public function show(): void
{
    $id = $this->input(INPUT_GET, 'id', FILTER_VALIDATE_INT);


    $item = $this->catalogService->single_item_array((int) $id);

    $this->render('details', [
        'pageTitle' => $item['title'],
        'section' => $item['category'],
        'item' => $item
    ]);
}
}