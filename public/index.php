<?php

use Core\Exception\Debug\Environment as Debug;
use Core\Exception\Handler as ExceptionHandler;
use Core\Routing\RouteParameters as Route;
use Core\Routing\Router;

$appBasePath = dirname(__DIR__);

require $appBasePath . '/vendor/autoload.php';

Dotenv\Dotenv::createImmutable($appBasePath)->safeLoad();

$logger  = new Core\Logger($appBasePath . '/logs/app.log');
$handler = new ExceptionHandler($logger, Debug::get());
$handler->register();

Core\View::addPath($appBasePath . '/app/views');

$router = new Router('/', 'App\Controllers');
$router->add('/', [
    Route::CONTROLLER => 'Home',
    Route::ACTION     => 'index',
]);
$router->add('/posts', [
    Route::CONTROLLER => 'Posts',
    Route::ACTION     => 'index',
]);
$router->add('/databases', [
    Route::CONTROLLER => 'Databases',
    Route::ACTION     => 'index',
]);
$router->add(sprintf('/{%s}', Route::CONTROLLER), [
    Route::ACTION => 'index',
]);
$router->dispatch($_SERVER['REQUEST_URI']);
