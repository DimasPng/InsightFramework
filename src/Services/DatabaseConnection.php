<?php

namespace App\Services;

use PDO;
use PDOException;

//сделать хранилищем или менеджером для PDO как в laravel
class DatabaseConnection
{
    protected PDO $connection;

    public function __construct(string $dsn, string $username, string $password)
    {
        try {
            $this->connection = new PDO($dsn, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error connecting to database: " . $e->getMessage();
            throw $e;
        }
    }

    public function query(string $sql): array
    {
        $stmt = $this->connection->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchColumn(string $sql): mixed
    {
        $stmt = $this->connection->query($sql);
        return $stmt->fetchColumn();
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}