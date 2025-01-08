<?php

namespace App\Core;

class App
{
    protected Router $router;
    public function __construct()
    {
        $this->router = new Router();
        $this->registerRoutes();
    }

    public function run(): void
    {
        $request = Request::capture();
        $this->router->dispatch($request);
    }

    protected function registerRoutes(): void
    {
        $this->router->add('', 'HomeController@index');
        $this->router->add('about', 'HomeController@about');
    }
}
