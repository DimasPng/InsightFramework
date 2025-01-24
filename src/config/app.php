<?php

return [
    'name' => 'My Mini Framework',
    /*
     |-----------------------------
     | Service Providers
     |-----------------------------
     */
    'providers' => [
        \App\Providers\DatabaseServiceProvider::class,
        \App\Providers\RouteServiceProvider::class,
        \App\Providers\AuthServiceProvider::class,
    ],
    'debug' => true
];
