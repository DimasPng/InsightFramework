<?php

namespace App\Core;

abstract class ServiceProvider
{
    public function __construct(
        protected Container $app
    )
    {
    }

    public function register(): void
    {
    }

    public function boot(): void
    {
    }
}
