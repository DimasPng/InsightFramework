<?php

namespace App\Http\Response;

class ResponseFactory implements ResponseFactoryInterface
{
    public function json(array $data, int $status = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    public function make(string $content = '', int $status = Response::HTTP_OK, array $headers = []): Response
    {
        return new Response($content, $status, $headers);
    }

    public function view(string $view, array $data = [], int $status = Response::HTTP_OK, array $headers = []): ViewResponse
    {
        return new ViewResponse($view, $data, $status, $headers);
    }

    public function redirect(string $url, int $status = Response::HTTP_FOUND): Response
    {
        return new Response('', $status, ['Location' => $url]);
    }
}
