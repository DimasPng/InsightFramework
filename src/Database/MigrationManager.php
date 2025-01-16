<?php

namespace App\Database;

use App\Core\Container;
use App\Services\DatabaseConnection;
use Exception;

class MigrationManager
{
    protected string $migrationsTable = 'migrations';
    protected string $migrationPath = __DIR__ . '/Migrations';

    public function __construct(
        protected DatabaseConnection $dbConn,
        protected Container          $container
    )
    {
        $this->ensureMigrationTable();
    }

    public function run(): void
    {
        $executedMigrations = $this->getExecutedMigrations();
        $migrationFiles = $this->getMigrationFiles();

        $pendingMigrations = array_diff($migrationFiles, $executedMigrations);
        if (empty($pendingMigrations)) {
            echo "All migrations are already executed.\n";
            return;
        }

        foreach ($pendingMigrations as $file) {
            /** @var Migration $migration */
            $migration = include "{$this->migrationPath}/{$file}";

            if (!$migration instanceof Migration) {
                throw new Exception("Migration {$file} does not return a valid Migration instance.");
            }

            $migration->up();
            $this->recordMigration($file);
            echo "Migrated: {$file}\n";
        }
    }

    public function rollback(): void
    {
        $stmt = $this->dbConn->getConnection()->query("
        SELECT migration FROM {$this->migrationsTable} ORDER BY id DESC LIMIT 1
            ");

        if ($stmt === false) {
            echo "Error: Failed to execute query or no migrations to rollback.\n";
            return;
        }

        $lastMigration = $stmt->fetchColumn();

        if ($lastMigration) {
            /** @var Migration $migration */
            $migration = include "{$this->migrationPath}/{$lastMigration}";
            $migration->down();

            $this->dbConn->getConnection()->exec("DELETE FROM {$this->migrationsTable} WHERE migration = '$lastMigration'");

            echo "Rolled back: {$lastMigration}\n";
        } else {
            echo "No migrations to rollback.\n";
        }
    }

    protected function ensureMigrationTable(): void
    {
        $this->dbConn->getConnection()->exec("
            CREATE TABLE IF NOT EXISTS {$this->migrationsTable} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    protected function extractClassName(string $file): string
    {
        $fileNameWithoutTimestamp = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $file);

        $classNameSnakeCase = pathinfo($fileNameWithoutTimestamp, PATHINFO_FILENAME);

        $className = str_replace(' ', '', ucwords(str_replace('_', ' ', $classNameSnakeCase)));

        return "App\\Database\\Migrations\\{$className}";
    }

    protected function getExecutedMigrations(): array
    {
        return array_column($this->dbConn->query("SELECT migration FROM {$this->migrationsTable}"), 'migration');
    }

    protected function getMigrationFiles(): array
    {
        return array_diff(scandir($this->migrationPath), ['.', '..']);
    }

    protected function recordMigration(string $migration): void
    {
        $stmt = $this->dbConn->getConnection()->prepare("INSERT INTO {$this->migrationsTable} (migration) VALUES (:migration)");
        $stmt->execute(['migration' => $migration]);
    }
}
