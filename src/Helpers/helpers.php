<?php

use App\Core\App;

if (!function_exists('app')) {
    function app(?string $key = null)
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