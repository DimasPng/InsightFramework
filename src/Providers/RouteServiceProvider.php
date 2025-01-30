<?php

namespace App\Providers;

use App\Core\Middleware\MiddlewareStack;
use App\Core\RouteCollection;
use App\Core\RouteFacade;
use App\Core\Router;
use App\Core\ServiceProvider;
use App\Http\Kernel;

class RouteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MiddlewareStack::class, function () {
            return new MiddlewareStack();
        });

        $this->app->singleton(Kernel::class, function ($app) {
            return new Kernel($app->make(MiddlewareStack::class));
        });

        $this->app->singleton(Router::class, function () {
            $routeCollection = $this->app->make(RouteCollection::class);
            $middleWareStack = $this->app->make(MiddlewareStack::class);

            return new Router($routeCollection, $middleWareStack);
        });
    }

    public function boot(): void
    {
        /** @var Kernel $kernel */
        $kernel = $this->app->make(Kernel::class);
        $kernel->handleMiddleware();

        /** @var Router $router */
        $router = $this->app->make(Router::class);

        RouteFacade::init($router);

        $router->group(['middleware' => 'web'], function () {
            require_once __DIR__ . '/../../routes/web.php';
        });

        $router->group(['middleware' => 'api'], function () {
            require_once __DIR__ . '/../../routes/api.php';
        });
    }
}