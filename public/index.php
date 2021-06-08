<?php

require __DIR__ . '/../vendor/autoload.php';

\Core\View::addPath(__DIR__ . '/../app/views');

$router = new \Core\Router();
$router->add('', [
    'controller' => 'home',
    'action'     => 'index',
]);
$router->add('{controller}/{action}');
$router->add('{namespace}/{controller}/{action}');
$router->dispatch($_SERVER['QUERY_STRING']);
