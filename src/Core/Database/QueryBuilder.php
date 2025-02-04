<?php

namespace App\Core\Database;

use App\Core\Model;
use Exception;
use PDO;

/**
 * @template T of Model
 */
class QueryBuilder
{
    protected array $conditions = [];
    protected array $bindings = [];
    protected int $bindingCounter = 0;

    public function __construct(
        protected string $modelClass
    )
    {
    }

    public function where(string $column, mixed $operatorOrValue, mixed $value = null): self
    {
        if ($value === null) {
            $operator = '=';
            $value = $operatorOrValue;
        } else {
            $operator = $operatorOrValue;
        }
        $this->bindingCounter++;

        $bindingKey = $column . $this->bindingCounter;
        $this->conditions[] = "{$column} {$operator} :{$bindingKey}";
        $this->bindings[$bindingKey] = $value;
        return $this;
    }

    protected function buildWhereClause(): string
    {
        if (empty($this->conditions)) {
            return '';
        }

        return ' WHERE ' . implode(' AND ', $this->conditions);
    }

    /**
     * @return T[]
     * @throws Exception
     */
    public function get(): array
    {
        /** @var Model $modelClass */
        $modelClass = $this->modelClass;
        $table = $modelClass::getTable();
        $sql = "SELECT * FROM {$table}" . $this->buildWhereClause();
        $db = $modelClass::getConnection();

        if (!$db) {
            throw new Exception("Database connection not set. Call Model::setConnection() first.");
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($this->bindings);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = $modelClass::newFromRow($row);
        }
        return $results;
    }

    /**
     * @return T|null
     * @throws Exception
     */
    public function first(): ?Model
    {
        /** @var Model $modelClass */
        $modelClass = $this->modelClass;
        $table = $modelClass::getTable();
        $sql = "SELECT * FROM {$table}" . $this->buildWhereClause() . " LIMIT 1";
        $db = $modelClass::getConnection();
        if (!$db) {
            throw new Exception("Database connection not set. Call Model::setConnection() first.");
        }
        $stmt = $db->prepare($sql);
        $stmt->execute($this->bindings);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return $modelClass::newFromRow($row);
    }

    /**
     * @param array $attributes
     * @return T
     */
    public function create(array $attributes): Model
    {
        $instance = $this->newModelInstance($attributes);
        $instance->save();
        return $instance;
    }

    /**
     * @param array $attributes
     * @return T
     */
    protected function newModelInstance(array $attributes = []): Model
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = $this->modelClass;
        return $modelClass::newInstance($attributes);
    }
}
