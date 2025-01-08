<?php

namespace App\Core;

use App\Controllers\HomeController;
use App\Services\ExampleService;

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
        $this->router->add('', 'HomeController@index');
        $this->router->add('about', 'HomeController@about');
    }

    protected function registerBindings(): void
    {
        $this->container->bind('ExampleService', function () {
            return new ExampleService();
        });

        $this->container->bind('HomeController', function (Container $container) {
            return new HomeController(
                $container->make('ExampleService')
            );
        });
    }
}
