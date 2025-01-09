<?php

namespace App\Core;

class Route
{
    protected array $middleware = [];
    public ?string $name = null;

    public function __construct(
        public string          $uri,
        protected string       $method,
        protected array|string $action
    )
    {
    }

    public function middleware(string|array $middleware): self
    {
        $this->middleware = is_array($middleware) ? $middleware : [$middleware];
        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDefinition(): array
    {
        return [
            'method' => $this->method,
            'uri' => $this->uri,
            'action' => $this->action,
            'middleware' => $this->middleware,
            'name' => $this->name
        ];
    }
}