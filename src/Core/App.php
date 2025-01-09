<?php

namespace App\Core;

use App\Services\DatabaseConnection;

class App
{
    protected Router $router;
    protected Container $container;

    public function __construct()
    {
        $this->router = new Router();
        $this->container = new Container();
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
        $this->container->singleton('App\Services\DatabaseConnection', function () {
            $dsn = sprintf('mysql:host=mysql;port=%s;dbname=%s;charset=utf8mb4',
                '3306',
                'framework'
            );

            return new DatabaseConnection($dsn, 'dimas', 'pass123');
        });
    }
}
