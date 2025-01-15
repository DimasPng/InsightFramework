<?php

namespace App\Core;

abstract class Command
{
    protected string $name = '';
    protected string $description = '';

    abstract public function handle(array $args): void;

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
