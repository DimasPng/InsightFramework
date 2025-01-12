<?php

namespace App\Providers;

use App\Core\RouteFacade;
use App\Core\Router;
use App\Core\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Router::class, function () {
            return new Router();
        });
    }

    public function boot(): void
    {
        $router = $this->app->make(Router::class);

        RouteFacade::init($router);

        foreach (glob(__DIR__ . '/../../routes/*.php') as $routeFile) {
            require_once $routeFile;
        }
    }
}