<?php

define('BASE_PATH', realpath(__DIR__ . '/../'));

spl_autoload_register(function ($class) {
    $roots = [
        BASE_PATH . '/app/'  => 'App\\',
        BASE_PATH . '/core/' => 'Core\\',
    ];
    foreach ($roots as $baseDir => $prefix) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }
        $relativeClass = substr($class, $len);

        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});

$router = new \Core\Router();
$router->add('', [
    'controller' => 'home',
    'action'     => 'index',
]);
$router->add('{controller}/{action}');
$router->add('{namespace}/{controller}/{action}');
$router->dispatch($_SERVER['QUERY_STRING']);
