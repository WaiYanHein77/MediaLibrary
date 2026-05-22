<?php

require_once BASE_PATH . '/Contract/CatalogRepositoryInterface.php';
require_once BASE_PATH . '/Repository/BaseRepository.php';

class CatalogRepository extends BaseRepository implements CatalogRepositoryInterface
{
    // Get total catalog count
    public function getcatalog_count($category = null, $search = null)
    {
        $stmt = $this->execute(
            "CALL sp_search_catalog_count(:search, :category)",
            [
                ':search' => $search ?: null,
                ':category' => $category ?: null
            ]
        );

        $count = $stmt->fetchColumn();

        $stmt->nextRowset();
        $stmt->closeCursor();

        return $count;
    }

    // Get full catalog list
    public function get_full_catalog($limit = null, $offset = 0)
    {
        $stmt = $this->execute(
            "CALL sp_get_full_catalog(?, ?)",
            [$limit, $offset]
        );

        $data = $stmt->fetchAll();
        $stmt->closeCursor();

        return $data;
    }

    // Get catalog by category
    public function get_category_catalog($category, $limit = null, $offset = 0)
    {
        $stmt = $this->execute(
            "CALL sp_get_catalog(?, ?, ?)",
            [$category, $limit, $offset]
        );

        $data = $stmt->fetchAll();
        $stmt->closeCursor();

        return $data;
    }

    // Search catalog
    public function get_search_catalog($search, $category = null, $limit = null, $offset = 0)
    {
        $search = $search === '' ? null : $search;
        $category = $category === '' ? null : $category;

        $stmt = $this->execute(
            "CALL sp_search_catalog(?, ?, ?, ?)",
            [$search, $category, $limit, $offset]
        );

        $data = $stmt->fetchAll();

        $stmt->nextRowset();
        $stmt->closeCursor();

        return $data;
    }

    // Random catalog
    public function get_random_catalog()
    {
        $stmt = $this->db->query("SELECT * FROM view_random");
        return $stmt->fetchAll();
    }

    // Single item detail
    public function get_single_item($id)
    {
        $stmt = $this->execute(
            "CALL sp_get_item_full_detail(?)",
            [$id]
        );

        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            $stmt->closeCursor();
            return null;
        }

        $stmt->nextRowset();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $role = strtolower($row['role']);
            $item[$role][] = $row['fullname'];
        }

        $stmt->closeCursor();

        return $item;
    }
}