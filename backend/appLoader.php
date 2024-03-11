<?php

$start = microtime(true);

use src\App\Services\CallerService\ServiceManager;
use src\App\Services\RouteService\Route;
use src\App\Services\RouteService\RouterManager;

// you can add new config file by adding to $configArray array.
$configArray = [
    include DIR . '/config/AppConfig.php',
    include DIR . '/config/database.php',
];

// Bootstrapping app
include DIR . '/src/App/Core/bootstrap.php';

// Initializing Router
$route = new Route();

// Setting Routes
include DIR . '/config/route.php';

// Handling Routes
$routeManager = new RouterManager();
$routeManager->handleMatches($route->match());

// Initializing Service Manager
$service = new ServiceManager();

echo round(microtime(true) - $start, 3).' c.';