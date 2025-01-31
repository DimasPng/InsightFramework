<?php

namespace App\Http\Response;

interface ResponseFactoryInterface
{
    public function json(array $data, int $status = Response::HTTP_OK, array $headers = []): JsonResponse;
    public function make(string $content = '', int $status = Response::HTTP_OK, array $headers = []): Response;
    public function view(string $view, array $data, int $status, array $headers): ViewResponse;
    public function redirect(string $url, int $status = Response::HTTP_FOUND): Response;
}
