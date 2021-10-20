<?php

use Core\Exception\Debug\Environment as Debug;
use Core\Routing\RouteParams as Route;

// Register a class autoloader.
require __DIR__ . '/../vendor/autoload.php';

// Load configuration from .env file.
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

// Create a logger specifying the path to the log file.
$logger = new Core\Logger(__DIR__ . '/../logs/app.log');

// Register an exception handler.
$handler = new Core\Exception\Handler($logger, Debug::get());
$handler->register();

// Add path to view files.
Core\View::addPath(__DIR__ . '/../app/views');

// Calculate base path of routes.
$baseRoute = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\\/');

// Add base route to views and create a router specifying the controllers namespace.
Core\View::addGlobal('baseRoute', $baseRoute);
$router = new Core\Routing\Router($baseRoute, 'App\Controllers');

// Add routes and dispatch the request URI.
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
$router->add(sprintf('/{%s}', Route::CONTROLLER), [
    Route::ACTION => 'index',
]);
$router->dispatch($_SERVER['REQUEST_URI']);
