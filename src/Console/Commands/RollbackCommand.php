<?php

namespace App\Console\Commands;

use App\Core\Command;
use App\Core\Container;
use App\Database\MigrationManager;

class RollbackCommand extends Command
{
    protected string $name = 'migrate:rollback';
    protected string $description = 'Rollback the last migration batch';

    public function __construct(
        protected Container $container
    )
    {
    }

    public function handle(array $args): void
    {
        /** @var MigrationManager $migrationManager */
        $migrationManager = $this->container->make(MigrationManager::class);
        $migrationManager->rollback();
    }
}