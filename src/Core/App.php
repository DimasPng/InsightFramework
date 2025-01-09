<?php

namespace App\Core;

use App\Services\DatabaseConnection;
use ReflectionException;

class App
{
    protected Router $router;
    protected Container $container;

    public function __construct()
    {
        $this->router = new Router();
        $this->container = new Container();

        RouteFacade::init($this->router);

        $this->registerRoutes();
        $this->registerBindings();
    }

    /**
     * @throws ReflectionException
     */
    public function run(): void
    {
        $request = Request::capture();
        $this->router->dispatch($request, $this->container);
    }

    protected function registerRoutes(): void
    {
        foreach (glob(__DIR__ . '/../../routes/*.php') as $routeFile) {
            require_once $routeFile;
        }
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
