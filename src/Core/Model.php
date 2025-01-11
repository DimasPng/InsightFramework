<?php

namespace App\Core;

use App\Services\DatabaseConnection;
use Exception;
use PDO;

abstract class Model
{
    //в laravel делается через менеджер соединений
    protected static ?PDO $db = null;
    protected static string $table;
    protected static string $primaryKey = 'id';
    //значения полей колонок
    protected array $attributes = [];
    protected array $fillable = [];

    public static function setConnection(DatabaseConnection $dbConnection): void
    {
        self::$db = $dbConnection->getConnection();
    }

    public static function getConnection(): ?PDO
    {
        return self::$db;
    }

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function fill(array $attributes): void
    {
        foreach($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->attributes[$key] = $value;
            }
        }
    }

    /**
     * @throws Exception
     */
    public static function find(int $id): ?static
    {
        self::ensureDbConnection();

        $table = static::$table;
        $pk = static::$primaryKey;

        $sql = "SELECT * FROM {$table} WHERE {$pk} = :id LIMIT 1";
        $stmt = self::$db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $model = new static();
        $model->forceFill($row);

        return $model;
    }

    /**
     * @throws Exception
     */
    public static function all(): array
    {
        self::ensureDbConnection();

        $table = static::$table;
        $sql = "SELECT * FROM {$table}";
        $stmt = self::$db->query($sql);

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $model = new static();
            $model->forceFill($row);
            $results[] = $model;
        }

        return $results;
    }

    public function save(): bool
    {
        self::ensureDbConnection();

        $table = static::$table;
        $pk = static::$primaryKey;

        if (!empty($this->attributes[$pk])) {
            $setClause = [];
            $params = [];

            foreach ($this->fillable as $field) {
                if (array_key_exists($field, $this->attributes)) {
                    $setClause[] = "{$field} = :{$field}";
                    $params[$field] = $this->attributes[$field];
                }
            }

            $params[$pk] = $this->attributes[$pk];

            $setSql = implode(', ', $setClause);
            $sql = "UPDATE {$table} SET {$setSql} WHERE {$pk} = :{$pk}";
            $stmt = self::$db->prepare($sql);

            return $stmt->execute($params);
        } else {
            // INSERT
            $fields = [];
            $placeholders = [];
            $params = [];

            foreach ($this->fillable as $field) {
                if (array_key_exists($field, $this->attributes)) {
                    $fields[] = $field;
                    $placeholders[] = ":{$field}";
                    $params[$field] = $this->attributes[$field];
                }
            }

            $fieldList = implode(', ', $fields);
            $placeholderList = implode(', ', $placeholders);

            $sql = "INSERT INTO {$table} ({$fieldList}) VALUES ({$placeholderList})";
            $stmt = self::$db->prepare($sql);
            $result = $stmt->execute($params);

            if ($result) {
                $this->attributes[$pk] = (int) self::$db->lastInsertId();
            }

            return  $result;
        }
    }

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, mixed $value): void
    {
        if (in_array($name, $this->fillable)) {
            $this->attributes[$name] = $value;
        }
    }

    protected function forceFill(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * @throws Exception
     */
    protected static function ensureDbConnection(): void
    {
        if (!self::$db) {
            throw new Exception("Database connection not set. Call Model::setConnection() first.");
        }
    }

}
