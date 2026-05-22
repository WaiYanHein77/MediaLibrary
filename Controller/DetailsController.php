<?php

require_once BASE_PATH . '/Controller/BaseController.php';

class DetailsController extends BaseController
{
    private CatalogService $catalogService;

    public function __construct(
        CatalogService $catalogService
    ) {
        $this->catalogService = $catalogService;
    }

    public function show(): void
    {
        $id = $this->input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        if (!$id) {
            $this->redirect(
                BASE_URL .
                "/Public/index.php?page=catalog"
            );
        }

        $item = $this->catalogService
            ->single_item_array($id);

        if (empty($item)) {
            $this->redirect(
                BASE_URL .
                "/Public/index.php?page=catalog"
            );
        }

        $this->render(
            'details',
            [
                'pageTitle' => $item['title'],
                'section' => $item['category'],
                'item' => $item
            ]
        );
    }
}