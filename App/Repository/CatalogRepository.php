<?php

namespace App\Repository;

use PDO;

use App\Contract\CatalogRepositoryInterface;

class CatalogRepository extends BaseRepository implements CatalogRepositoryInterface
{
    /*
     * TABLE CONFIGURATION
     */
    protected string $table = 'view_catalog';

    protected string $primaryKey = 'media_id';

    /*
     * OPTIONAL STORED PROCEDURES
     */
    protected ?string $countProcedure =
        'sp_search_catalog_count';

    protected ?string $getByIdProcedure =
        'sp_get_item_full_detail';

    // Uncomment if you want getAll()
    // -> CALL sp_get_full_catalog()
    
    protected ?string $getAllProcedure =
        'sp_get_full_catalog';
    


    /*
     * GET BY CATEGORY
     */
    public function getByCategory(
        string $category,
        ?int $limit = null,
        int $offset = 0
    ): array {

        $stmt = $this->db->prepare(
            "CALL sp_get_catalog(?, ?, ?)"
        );

        $stmt->bindValue(
            1,
            $category,
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            2,
            $limit,
            $limit === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_INT
        );

        $stmt->bindValue(
            3,
            $offset,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $catalog = $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );

        $stmt->closeCursor();

        return $catalog;
    }


    /*
     * SEARCH
     */
    public function search(
        string $keyword,
        ?string $category = null,
        ?int $limit = null,
        int $offset = 0
    ): array {

        $keyword =
            $keyword === ''
            ? null
            : $keyword;

        $category =
            $category === ''
            ? null
            : $category;

        $stmt = $this->db->prepare(
            "CALL sp_search_catalog(
                ?,
                ?,
                ?,
                ?
            )"
        );

        $stmt->bindValue(
            1,
            $keyword,
            $keyword === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_STR
        );

        $stmt->bindValue(
            2,
            $category,
            $category === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_STR
        );

        $stmt->bindValue(
            3,
            $limit,
            $limit === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_INT
        );

        $stmt->bindValue(
            4,
            $offset,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $catalog = $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );

        $stmt->nextRowset();

        $stmt->closeCursor();

        return $catalog;
    }


    /*
     * RANDOM
     */
    public function getRandom(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM view_random"
        );

        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }
}