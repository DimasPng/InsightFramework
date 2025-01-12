<?php

namespace App\Providers;

use App\Core\Model;
use App\Core\ServiceProvider;
use App\Services\DatabaseConnection;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DatabaseConnection::class, function () {
            $dsn = sprintf('mysql:host=mysql;port=%s;dbname=%s;charset=utf8mb4',
                '3306',
                'framework'
            );

            return new DatabaseConnection($dsn, 'dimas', 'pass123');
        });
    }

    public function boot(): void
    {
        $dbConn = $this->app->make(DatabaseConnection::class);

        Model::setConnection($dbConn);
    }
}
