<?php

/**
 * Handles catalog business logic and communicates
 * between controllers and the catalog repository.
 */

/* class CatalogService
{
    private CatalogRepositoryInterface $repo;

    public function __construct(?CatalogRepositoryInterface $repo = null)
    {
        // Create default repository if none is provided
        if ($repo === null) {
            $db = Database::getConnection();
            $repo = new CatalogRepository($db);
        }

        $this->repo = $repo;
    }

    // Get total number of catalog items
    public function get_catalog_count($category = null, $search = null)
    {
        return $this->repo->getcatalog_count($category, $search);
    }

    // Get all catalog items with pagination support
    public function full_catalog_array($limit = null, $offset = 0)
    {
        return $this->repo->get_full_catalog($limit, $offset);
    }

    // Get catalog items by category
    public function category_catalog_array($category, $limit = null, $offset = 0)
    {
        return $this->repo->get_category_catalog($category, $limit, $offset);
    }

    // Search catalog items by keyword and category
    public function search_catalog_array($search, $category = null, $limit = null, $offset = 0)
    {
        return $this->repo->get_search_catalog($search, $category, $limit, $offset);
    }

    // Get random catalog items
    public function random_catalog_array()
    {
        return $this->repo->get_random_catalog();
    }

    // Get a single catalog item by ID
    public function single_item_array($id)
    {
        return $this->repo->get_single_item($id);
    }
} */
require_once BASE_PATH . '/Repository/CatalogRepository.php';

class CatalogService
{
    private const ITEMS_PER_PAGE = 8;

    private const ALLOWED_CATEGORIES = [
        'books',
        'movies',
        'music'
    ];

    private CatalogRepository $repo;

    public function __construct(
        ?CatalogRepositoryInterface $repo = null
    ) {
        if ($repo === null) {
            $db = Database::getConnection();

            $repo = new CatalogRepository($db);
        }

        $this->repo = $repo;
    }

    /* =========================================================
     * MAIN METHOD
     * ========================================================= */
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

    // ✅ ADD THESE (FIX FOR YOUR ERROR)
    'totalItems' => $totalItems,
    'found_in_full_catalog' => ($section === null && $search === null)
];
    }

    /* =========================================================
     * CATEGORY FILTER
     * ========================================================= */
    private function getCategory(array $queryParams): ?string
    {
        $category = $queryParams['cat'] ?? null;

        if (
            $category !== null &&
            in_array($category, self::ALLOWED_CATEGORIES, true)
        ) {
            return $category;
        }

        return null;
    }

    /* =========================================================
     * SEARCH FILTER
     * ========================================================= */
    private function getSearchTerm(array $queryParams): ?string
    {
        $search = trim($queryParams['s'] ?? '');

        return $search !== '' ? $search : null;
    }

    /* =========================================================
     * PAGE NUMBER
     * ========================================================= */
    private function getCurrentPage(array $queryParams): int
    {
        $page = filter_var(
            $queryParams['pg'] ?? 1,
            FILTER_VALIDATE_INT
        );

        return ($page === false || $page < 1) ? 1 : $page;
    }

    /* =========================================================
     * PAGINATION
     * ========================================================= */
    private function buildPagination(int $totalItems, int $currentPage): array
    {
        $totalPages = max(
            1,
            (int) ceil($totalItems / self::ITEMS_PER_PAGE)
        );

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

    /* =========================================================
     * LOAD DATA (USES YOUR REPOSITORY)
     * ========================================================= */
    private function loadCatalogData(
        ?string $section,
        ?string $search,
        int $limit,
        int $offset
    ): array {
        if ($search !== null && $section !== null) {
            return $this->repo->get_search_catalog($search, $section, $limit, $offset);
        }

        if ($search !== null) {
            return $this->repo->get_search_catalog($search, null, $limit, $offset);
        }

        if ($section !== null) {
            return $this->repo->get_category_catalog($section, $limit, $offset);
        }

        return $this->repo->get_full_catalog($limit, $offset);
    }
    /* =========================================================
     * PAGE TITLE
     * ========================================================= */
    private function buildPageTitle(?string $section): string
    {
        return $section ? ucfirst($section) : "Full Catalog";
    }

    /* =========================================================
     * COUNT (FIXED - USE REPOSITORY METHOD)
     * ========================================================= */
    private function get_catalog_count(?string $section, ?string $search): int
    {
        return (int) $this->repo->getcatalog_count($section, $search);
    }

    /* =========================================================
     * RANDOM ITEMS
     * ========================================================= */
    public function random_catalog_array(): array
    {
        return $this->repo->get_random_catalog();
    }

    /* =========================================================
     * SINGLE ITEM DETAIL
     * =========================================================
     */
    public function single_item_array(int $id): ?array
    {
        return $this->repo->get_single_item($id);
    }
}