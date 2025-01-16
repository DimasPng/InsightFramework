<?php

namespace App\Database;

use App\Services\DatabaseConnection;
use Exception;

class Schema
{
    protected static ?DatabaseConnection $dbConnection = null;
    public static function setConnection(DatabaseConnection $connection): void
    {
        self::$dbConnection = $connection;
    }

    public static function create(string $table, callable $callback): void
    {
        self::ensureConnection();

        $blueprint = app()->getContainer()->make(Blueprint::class, ['table' => $table]);
        $callback($blueprint);
        $sql = $blueprint->toSql();
        self::execute($sql);
    }

    protected static function execute(string $sql): void
    {
        self::ensureConnection();

        self::$dbConnection->getConnection()->exec($sql);
    }

    protected static function ensureConnection(): void
    {
        if (!self::$dbConnection) {
            throw new Exception("Database connection not set. Call Schema::setConnection() first.");
        }
    }

    public static function dropIfExists(string $table): void
    {
        $sql = "DROP TABLE IF EXISTS {$table}";
        self::execute($sql);
    }
}
