<?php

namespace App\Repository;

use PDO;
use App\Contract\BaseRepositoryInterface;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected PDO $db;

    protected string $table;

    protected string $primaryKey = 'id';

    // Optional stored procedures (override per repository if needed)
    protected ?string $getByIdProcedure = null;
    protected ?string $getAllProcedure = null;
    protected ?string $countProcedure = null;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /*
     * GET ALL (BaseRepositoryInterface)
     */
    public function getAll(?int $limit = null, int $offset = 0): array
    {
        // =========================
        // STORED PROCEDURE PATH
        // =========================
        if ($this->getAllProcedure !== null) {

            $stmt = $this->db->prepare(
                "CALL {$this->getAllProcedure}(?, ?)"
            );

            $stmt->bindValue(
                1,
                $limit,
                $limit === null ? PDO::PARAM_NULL : PDO::PARAM_INT
            );

            $stmt->bindValue(2, $offset, PDO::PARAM_INT);

        } else {

            // =========================
            // TABLE FALLBACK PATH
            // =========================
            $sql = "SELECT * FROM {$this->table}";

            if ($limit !== null) {
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->db->prepare($sql);

            if ($limit !== null) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
        }

        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $data;
    }

    /*
     * GET BY ID (BaseRepositoryInterface)
     */
    public function getById(int $id): ?array
    {
        // =========================
        // STORED PROCEDURE PATH
        // =========================
        if ($this->getByIdProcedure !== null) {

            $stmt = $this->db->prepare(
                "CALL {$this->getByIdProcedure}(?)"
            );

            $stmt->bindValue(1, $id, PDO::PARAM_INT);

        } else {

            // =========================
            // TABLE FALLBACK PATH
            // =========================
            $stmt = $this->db->prepare(
                "SELECT * FROM {$this->table}
                 WHERE {$this->primaryKey} = :id
                 LIMIT 1"
            );

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        }

        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $data ?: null;
    }

    /*
     * COUNT (BaseRepositoryInterface)
     */
    public function count(array $filters = []): int
    {
        $search = $filters['search'] ?? null;
        $category = $filters['category'] ?? null;

        // =========================
        // STORED PROCEDURE PATH
        // =========================
        if ($this->countProcedure !== null) {

            $stmt = $this->db->prepare(
                "CALL {$this->countProcedure}(:search, :category)"
            );

            $stmt->bindValue(
                ':search',
                $search,
                $search === null ? PDO::PARAM_NULL : PDO::PARAM_STR
            );

            $stmt->bindValue(
                ':category',
                $category,
                $category === null ? PDO::PARAM_NULL : PDO::PARAM_STR
            );

        } else {

            // =========================
            // TABLE FALLBACK PATH
            // =========================
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";

            if ($search !== null) {
                $sql .= " AND title LIKE :search";
            }

            if ($category !== null) {
                $sql .= " AND category = :category";
            }

            $stmt = $this->db->prepare($sql);

            if ($search !== null) {
                $stmt->bindValue(':search', "%{$search}%", PDO::PARAM_STR);
            }

            if ($category !== null) {
                $stmt->bindValue(':category', $category, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        $count = (int) $stmt->fetchColumn();
        $stmt->closeCursor();

        return $count;
    }
}