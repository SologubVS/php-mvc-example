<?php

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();

$isDebug = Core\Exception\Debug\Environment::get();
$handler = new Core\Exception\Handler(new Core\Logger(__DIR__ . '/../logs/app.log'), $isDebug);
$handler->register();

Core\View::addPath(__DIR__ . '/../app/views');

$router = new Core\Routing\Router('/', 'App\Controllers');
$router->add('/', [
    'controller' => 'Home',
    'action'     => 'index',
]);
$router->add('/posts', [
    'controller' => 'Posts',
    'action'     => 'index',
]);
$router->add('/databases', [
    'controller' => 'Databases',
    'action'     => 'index',
]);
$router->add(sprintf('/{%s}', 'controller'), ['action' => 'index']);
$router->dispatch($_SERVER['REQUEST_URI']);
