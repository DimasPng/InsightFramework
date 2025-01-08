<?php

namespace App\Core;

class Request
{
    private function __construct(
        protected string $uri,
        protected string $method
    )
    {
    }

    public static function capture(): self
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $method = $_SERVER['REQUEST_METHOD'];
        return new self($uri, $method);
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function method(): string
    {
        return $this->method;
    }
}