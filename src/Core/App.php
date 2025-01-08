<?php

namespace App\Core;

class App
{
    protected Router $router;
    protected Container $container;
    public function __construct()
    {
        $this->container = new Container();
        $this->router = new Router();
        $this->registerRoutes();
        $this->registerBindings();
    }

    public function run(): void
    {
        $request = Request::capture();
        $this->router->dispatch($request, $this->container);
    }

    protected function registerRoutes(): void
    {
        $this->router->add('', 'App\Controllers\HomeController@index');
        $this->router->add('about', 'App\Controllers\HomeController@about');
    }

    protected function registerBindings(): void
    {
    }
}
