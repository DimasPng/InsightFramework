<?php

namespace App\Core;

use ReflectionException;

class App
{
    protected Container $container;
    protected array $config = [];

    public function __construct()
    {
        $this->container = new Container();
        $this->container->singleton(Container::class, fn($container) => $container);
        $this->loadConfig();
        $this->registerProviders();
    }

    /**
     * @throws ReflectionException
     */
    public function run(): void
    {
        $request = Request::capture();

        $this->container->make(Router::class)->dispatch($request, $this->container);
    }

    protected function loadConfig(): void
    {
        $this->config = require __DIR__ . '/../config/app.php';
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
