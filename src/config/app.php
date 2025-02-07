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
        \dimaspng\oauth2auth\Providers\OAuthJWTServiceProvider::class
    ],
    'debug' => true
];
