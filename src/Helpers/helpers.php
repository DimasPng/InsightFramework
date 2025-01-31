<?php

use App\Core\App;
use App\Http\Response\Response;
use App\Http\Response\ResponseFactory;
use App\Http\Response\ViewResponse;

if (!function_exists('app')) {
    function app(?string $key = null): App
    {
        $app = App::getInstance();

        if (!$app) {
            throw new RuntimeException('Application instance has not been initialized.');
        }

        if ($key) {
            return $app->getContainer()->make($key);
        }

        return $app;
    }
}

if (!function_exists('response')) {
    function response(): ResponseFactory
    {
        return app()->getContainer()->make(ResponseFactory::class);
    }
}

if (!function_exists('view')) {
    function view(string $view, array $data = [], int $status = Response::HTTP_OK, array $headers = []): ViewResponse
    {
        return app()->getContainer()->make(ResponseFactory::class)->view($view, $data, $status, $headers);
    }
}