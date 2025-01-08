<?php

namespace App\Core;

class App
{
    public function __construct(
        public Router $router
    )
    {
    }

    public function run(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        $this->router->dispatch($uri);
    }
}
