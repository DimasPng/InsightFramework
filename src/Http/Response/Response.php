<?php

namespace App\Http\Response;

class Response
{
    protected string $content = '';
    protected int $statusCode = 200;
    protected array $headers = [];

    public const int HTTP_OK = 200;
    public const int HTTP_CREATED = 201;
    public const int HTTP_NO_CONTENT = 204;
    public const int HTTP_FOUND = 302;
    public const int HTTP_BAD_REQUEST = 400;
    public const int HTTP_UNAUTHORIZED = 401;
    public const int HTTP_FORBIDDEN = 403;
    public const int HTTP_NOT_FOUND = 404;
    public const int INTERNAL_SERVER_ERROR = 500;

    public function __construct(string $content = '', int $statusCode = self::HTTP_OK, array  $headers = [])
    {
        $this->setContent($content);
        $this->setStatusCode($statusCode);
        $this->headers = $headers;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setHeaders(int $key, string $value): static
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");

            echo $this->content;
            exit;
        }
    }
}
