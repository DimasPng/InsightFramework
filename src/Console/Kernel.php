<?php

namespace App\Console;

use App\Core\Command;
use App\Core\Container;
use ReflectionClass;

class Kernel
{
    protected array $commands = [];
    public function __construct(
        protected Container $container
    )
    {
        $this->loadCommands();
    }

    protected function loadCommands(): void
    {
        foreach (glob(__DIR__ . '/Commands/*.php') as $file) {
            require_once $file;
            $className = $this->getClassNameFromFile($file);

            if (is_subclass_of($className, Command::class) && !(new ReflectionClass($className))->isAbstract()) {
                $this->commands[] = $this->container->make($className);
            }

        }
    }
    protected function getClassNameFromFile(string $file): string
    {
        $namespace = 'App\\Console\\Commands\\';
        return $namespace . basename($file, '.php');
    }

    public function handle(string $name, array $args): void
    {
        foreach ($this->commands as $command) {
            if ($command->getName() === $name) {
                $command->handle($args);
                return;
            }
        }

        echo "Command {$name} not found.\n";
    }

    public function listCommands(): void
    {
        echo "Available commands:\n";
        foreach ($this->commands as $command) {
            echo " - {$command->getName()}: {$command->getDescription()}\n";
        }
    }
}
