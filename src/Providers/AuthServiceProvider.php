<?php

namespace App\Providers;

use App\Core\Auth\AuthManager;
use App\Core\Auth\DatabaseUserProvider;
use App\Core\Auth\SessionGuard;
use App\Core\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AuthManager::class, function () {
            return new AuthManager();
        });

        //TODO: Make Facade auth
//        $this->app->singleton('auth', function () {
//            return new AuthManager();
//        });
    }

    public function boot(): void
    {
        /** @var AuthManager $authManager */
        $authManager = $this->app->make(AuthManager::class);

        $authManager->setDefaultGuard('web');
        $authManager->resolveGuardUsing('web', function () use ($authManager) {
            $provider = $this->app->make(DatabaseUserProvider::class);

            return new SessionGuard($provider);
        });
    }
}
