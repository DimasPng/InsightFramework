<?php

namespace App\Database;

class Blueprint
{
    protected array $columns = [];
    protected array $primaryKeys = [];
    protected array $uniqueKeys = [];
    protected array|bool $timestamps = false;

    public function __construct(
        protected string $table
    )
    {
    }

    public function id(): void
    {
        $this->columns[] = "id INT AUTO_INCREMENT PRIMARY KEY";
    }

    public function string(string $name): self
    {
        $this->columns[] = "{$name} VARCHAR(255)";
        return $this;
    }

    public function nullable(): self
    {
        $lastColumn = array_pop($this->columns);
        $this->columns[] = "{$lastColumn} NULL";
        return $this;
    }

    public function unique(): self
    {
        $lastColumn = array_pop($this->columns);
        $this->columns[] = "{$lastColumn} UNIQUE";
        return $this;
    }

    public function timestamp(string $name): self
    {
        $this->columns[] = "{$name} TIMESTAMP NULL";
        return $this;
    }

    public function timestamps(): void
    {
        $this->columns[] = "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $this->columns[] = "updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
    }

    public function rememberToken(): void
    {
        $this->columns[] = "remember_token VARCHAR(100) NULL";
    }

    public function integer(string $name): self
    {
        $this->columns[] = "{$name} INT";
        return $this;
    }

    public function bigInteger(string $name): self
    {
        $this->columns[] = "{$name} BIGINT";
        return $this;
    }

    public function decimal(string $name, int $precision = 8, int $scale = 2): self
    {
        $this->columns[] = "{$name} DECIMAL ({$precision}, {$scale})";
        return $this;
    }

    public function toSql(): string
    {
        $columns = implode(", ", $this->columns);
        return "CREATE TABLE {$this->table} ({$columns})";
    }
}
