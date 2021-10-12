<?php

use Core\Exception\Debug\Environment as Debug;
use Core\Exception\Handler as ExceptionHandler;
use Core\Routing\RouteParams as Route;
use Core\Routing\Router;

[$appBasePath, $routeBasePath] = [
    dirname(__DIR__),
    rtrim(dirname($_SERVER['SCRIPT_NAME']), '\\/'),
];

require $appBasePath . '/vendor/autoload.php';

Dotenv\Dotenv::createImmutable($appBasePath)->safeLoad();

$logger  = new Core\Logger($appBasePath . '/logs/app.log');
$handler = new ExceptionHandler($logger, Debug::get());
$handler->register();

Core\View::addPath($appBasePath . '/app/views');
Core\View::addGlobal('baseRoute', $routeBasePath);

$router = new Router($routeBasePath, 'App\Controllers');
$router->add('/', [
    Route::CONTROLLER => 'Home',
    Route::ACTION     => 'index',
]);
$router->add('/posts', [
    Route::CONTROLLER => 'Posts',
    Route::ACTION     => 'index',
]);
$router->add('/posts/{slug}', [
    Route::CONTROLLER => 'Posts',
    Route::ACTION     => 'show',
]);
$router->add('/databases', [
    Route::CONTROLLER => 'Databases',
    Route::ACTION     => 'index',
]);
$router->add(sprintf('/{%s}', Route::CONTROLLER), [
    Route::ACTION => 'index',
]);
$router->dispatch($_SERVER['REQUEST_URI']);
