<?php

abstract class BaseRepository
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Execute stored procedure or SQL with parameters
     */
    protected function execute(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $type = match (true) {
                is_null($value) => PDO::PARAM_NULL,
                is_int($value) => PDO::PARAM_INT,
                default => PDO::PARAM_STR,
            };

            // named or positional support
            if (is_int($key)) {
                $stmt->bindValue($key + 1, $value, $type);
            } else {
                $stmt->bindValue($key, $value, $type);
            }
        }

        $stmt->execute();
        return $stmt;
    }
}