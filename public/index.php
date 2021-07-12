<?php

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();

Core\View::addPath(__DIR__ . '/../app/views');

$isDebug = Core\Exception\Debug\Environment::get();
$handler = new Core\Exception\Handler(new Core\Logger(__DIR__ . '/../logs/app.log'), $isDebug);
$handler->register();

$router = new Core\Router();
$router->add('', [
    'controller' => 'home',
    'action' => 'index',
]);
$router->add('{controller}', [
    'action' => 'index',
]);
$router->add('{controller}/{action}');
$router->dispatch($_SERVER['QUERY_STRING']);
