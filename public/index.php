<?php

spl_autoload_register(function ($class) {
    $roots = [
        __DIR__ . '/../app/'  => 'App\\',
        __DIR__ . '/../core/' => 'Core\\',
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
$router->dispatch($_SERVER['QUERY_STRING']);
