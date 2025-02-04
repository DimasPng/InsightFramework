<?php

namespace App\Core;

use App\Models\User;

class Request
{
    private ?User $user = null;
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

    public function header(string $name, $default = null): ?string
    {
        $name = strtolower($name);
        $headers = $this->getAllHeaders();
        return $headers[$name] ?? $default;
    }

    private function getAllHeaders(): array
    {
        $headers = [];

        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headerName = strtolower(str_replace('_', '-', substr($key, 5)));
                $headers[$headerName] = $value;
            }
        }

        if (isset($_SERVER['CONTENT_TYPE'])) {
            $headers['content-type'] = $_SERVER['CONTENT_TYPE'];
        }

        if (isset($_SERVER['CONTENT_LENGTH'])) {
            $headers['content-length'] = $_SERVER['CONTENT_LENGTH'];
        }

        return $headers;
    }

    public function only(array $keys): array
    {
        $data = $this->getInputData();
        return array_intersect_key($data, array_flip($keys));
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function query(string $key = null, mixed $default = null): mixed
    {
        $queryParams = [];
        parse_str($_SERVER['QUERY_STRING'] ?? '', $queryParams);

        if (is_null($key)) {
            return $queryParams;
        }

        return $queryParams[$key] ?? $default;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    private function getInputData(): array
    {
        $data = $_REQUEST;

        if ($this->isJson()) {
            $jsonInput = file_get_contents('php://input');
            $decoded = json_decode($jsonInput, true);
            if (is_array($decoded)) {
                $data = array_merge($data, $decoded);
            }
        }

        return $data;
    }

    private function isJson(): bool
    {
        $contentType = $this->header('content_type', '');
        return str_contains($contentType, 'application/json');
    }
}
