<?php

namespace App\Service;

use App\Contract\CatalogRepositoryInterface;
use App\Inc\Database as IncDatabase;
use App\Repository\CatalogRepository;

class CatalogService
{
    private const ITEMS_PER_PAGE = 8;

    private const ALLOWED_CATEGORIES = [
        'books',
        'movies',
        'music'
    ];

    private CatalogRepositoryInterface $repo;

    public function __construct(?CatalogRepositoryInterface $repo = null)
    {
        if ($repo === null) {
            $db = IncDatabase::getConnection();
            $repo = new CatalogRepository($db);
        }

        $this->repo = $repo;
    }

    public function getCatalogPage(array $queryParams): array
    {
        $section = $this->getCategory($queryParams);
        $search = $this->getSearchTerm($queryParams);
        $currentPage = $this->getCurrentPage($queryParams);

        $totalItems = $this->get_catalog_count($section, $search);

        $pagination = $this->buildPagination($totalItems, $currentPage);

        $catalog = $this->loadCatalogData(
            $section,
            $search,
            $pagination['limit'],
            $pagination['offset']
        );

        return [
            'catalog' => $catalog,
            'section' => $section,
            'search' => $search,
            'currentPage' => $pagination['currentPage'],
            'totalPages' => $pagination['totalPages'],
            'pageTitle' => $this->buildPageTitle($section),
            'totalItems' => $totalItems,
            'found_in_full_catalog' => ($section === null && $search === null)
        ];
    }

    /* ================= CATEGORY ================= */
    private function getCategory(array $queryParams): ?string
    {
        $category = $queryParams['cat'] ?? null;

        if ($category && in_array($category, self::ALLOWED_CATEGORIES, true)) {
            return $category;
        }

        return null;
    }

    /* ================= SEARCH ================= */
    private function getSearchTerm(array $queryParams): ?string
    {
        $search = trim($queryParams['s'] ?? '');
        return $search !== '' ? $search : null;
    }

    /* ================= PAGE ================= */
    private function getCurrentPage(array $queryParams): int
    {
        $page = filter_var($queryParams['pg'] ?? 1, FILTER_VALIDATE_INT);
        return ($page === false || $page < 1) ? 1 : $page;
    }

    /* ================= PAGINATION ================= */
    private function buildPagination(int $totalItems, int $currentPage): array
    {
        $totalPages = max(1, (int) ceil($totalItems / self::ITEMS_PER_PAGE));

        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        $offset = ($currentPage - 1) * self::ITEMS_PER_PAGE;

        return [
            'limit' => self::ITEMS_PER_PAGE,
            'offset' => $offset,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ];
    }

    /* ================= LOAD DATA (FIXED) ================= */
    private function loadCatalogData(
        ?string $section,
        ?string $search,
        int $limit,
        int $offset
    ): array {
        // SEARCH + CATEGORY
        if ($search !== null && $section !== null) {
            return $this->repo->search($search, $section, $limit, $offset);
        }

        // ONLY SEARCH
        if ($search !== null) {
            return $this->repo->search($search, null, $limit, $offset);
        }

        // ONLY CATEGORY
        if ($section !== null) {
            return $this->repo->getByCategory($section, $limit, $offset);
        }

        // FULL LIST
        return $this->repo->getAll($limit, $offset);
    }

    /* ================= TITLE ================= */
    private function buildPageTitle(?string $section): string
    {
        return $section ? ucfirst($section) : "Full Catalog";
    }

    /* ================= COUNT (FIXED) ================= */
    private function get_catalog_count(?string $section, ?string $search): int
    {
        return $this->repo->count([
            'category' => $section,
            'search' => $search
        ]);
    }

    /* ================= RANDOM ================= */
    public function random_catalog_array(): array
    {
        return $this->repo->getRandom();
    }

    /* ================= SINGLE ITEM ================= */
    public function single_item_array(int $id): ?array
    {
        return $this->repo->getById($id);
    }
}