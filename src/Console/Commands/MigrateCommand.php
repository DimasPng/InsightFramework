<?php

namespace App\Console\Commands;

use App\Core\Command;
use App\Core\Container;
use App\Database\MigrationManager;

class MigrateCommand extends Command
{
    protected string $name = 'migrate';
    protected string $description = 'Run all pending migrations';

    public function __construct(
        protected Container $container
    )
    {
    }

    public function handle(array $args): void
    {
        /** @var MigrationManager $migrationManager */
        $migrationManager = $this->container->make(MigrationManager::class);
        $migrationManager->run();
    }
}