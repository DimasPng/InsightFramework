<?php

namespace App\Core;

class App
{
    protected static ?self $instance = null;
    protected Container $container;
    protected array $config = [];

    public function __construct()
    {
        $this->container = new Container();
        $this->container->singleton(Container::class, fn(Container $container) => $container);
        $this->container->singleton(Request::class, fn() => Request::capture());
        $this->loadConfig();
        $this->registerProviders();

        self::$instance = $this;
    }

    public function run(): void
    {
        $request = $this->container->make(Request::class);

        /** @var Router $router */
        $router = $this->container->make(Router::class);

        $router->dispatch($request, $this->container);
    }

    protected function loadConfig(): void
    {
        $this->config = require __DIR__ . '/../config/app.php';
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    public static function getInstance(): ?self
    {
        return self::$instance;
    }

    protected function registerProviders(): void
    {
        if (!isset($this->config['providers']) || !is_array($this->config['providers'])) {
            return;
        }

        foreach ($this->config['providers'] as $providerClass) {
            /** @var ServiceProvider $provider */
            $provider = $this->container->make($providerClass);

            $provider->register();
            $provider->boot();
        }
    }
}
