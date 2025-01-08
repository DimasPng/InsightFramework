<?php

require_once(__DIR__ . "/../vendor/autoload.php");

use App\Core\App;
use App\Core\Router;

$router = new Router();
$app = new App($router);

$app->router->add('/', 'HomeController', 'index');
$app->router->add('/about', 'HomeController', 'about');

$app->run();