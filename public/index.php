<?php

use Core\Routing\RouteParameters as Route;

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();

$isDebug = Core\Exception\Debug\Environment::get();
$handler = new Core\Exception\Handler(new Core\Logger(__DIR__ . '/../logs/app.log'), $isDebug);
$handler->register();

Core\View::addPath(__DIR__ . '/../app/views');

$router = new Core\Routing\Router('/', 'App\Controllers');
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
