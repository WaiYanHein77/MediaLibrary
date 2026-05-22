<?php

require_once BASE_PATH . '/Contract/FormatRepositoryInterface.php';
require_once BASE_PATH . '/Repository/BaseRepository.php';

class FormatRepository extends BaseRepository implements FormatRepositoryInterface
{
    // Formats by category
    public function get_format_drop_down($category = null)
    {
        $stmt = $this->execute(
            "CALL sp_get_formats_by_category(:category)",
            [':category' => $category ?: null]
        );

        $format = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $format[$row["category"]][] = $row["format"];
        }

        $stmt->closeCursor();

        return $format;
    }

    // Categories list
    public function get_category_drop_down()
    {
        $stmt = $this->db->prepare(
            "SELECT DISTINCT category FROM view_catalog ORDER BY category"
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Genres by category
    public function get_genres_drop_down($category = null)
    {
        $stmt = $this->execute(
            "CALL sp_get_genres_by_category(:category)",
            [':category' => $category ?: null]
        );

        $genre = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $genre[$row["category"]][] = $row["genre"];
        }

        $stmt->closeCursor();

        return $genre;
    }
}