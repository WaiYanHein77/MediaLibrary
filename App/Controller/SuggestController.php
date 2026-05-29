<?php

namespace App\Controller;

use App\Service\FormatService;
use App\Service\SuggestService;
use App\Request\SuggestRequest;

class SuggestController extends BaseController
{
    public function __construct(
        private FormatService $formatService,
        private SuggestService $suggestService
    ) {}

    public function index(SuggestRequest $request)
    {
        return $this->form(
            $request,

            // SERVICE CALL
            fn($dto) => $this->suggestService->send($dto),

            // VIEW
            'suggest',

            // SUCCESS REDIRECT
            'index.php?page=suggest&status=thanks',

            // VIEW DATA
            [
                'pageTitle' => 'Suggest a media item',
                'section' => 'suggest',
                'hideSearch' => true,

                'categories' => $this->formatService->category_drop_down(),
                'formats' => $this->formatService->format_array(),
                'genres' => $this->formatService->genres_array(),

                // default form values
                'name' => null,
                'email' => null,
                'category' => null,
                'title' => null,
                'format' => null,
                'genre' => null,
                'year' => null,
                'details' => null
            ]
        );
    }
}