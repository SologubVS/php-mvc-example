<?php

namespace Core;

use Core\Exception\HttpException;

class Router
{
    /**
     * Controllers namespace.
     *
     * Relative controller names will be prefixed with this namespace.
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * Controller name.
     *
     * @var string
     */
    protected $controller = '';

    /**
     * Controller action name.
     *
     * @var string
     */
    protected $action = '';

    /**
     * Array of routes (the routing table).
     *
     * @var array
     */
    protected $routes = [];

    /**
     * Parameters from the matched route.
     *
     * @var array
     */
    protected $params = [];

    /**
     * Create a new router.
     *
     * @param string $namespace Controllers namespace.
     */
    public function __construct(string $namespace = '')
    {
        $this->namespace = rtrim($namespace, '\\');
    }

    /**
     * Add a route to the routing table.
     *
     * Before being added to the routing table,
     * the route string is converted to a regular expression,
     * and can contain variables in the {variable} format.
     *
     * By default, the regular expression [a-z-]+ is used to retrieve
     * the value of a variable. It is possible to specify a different
     * regular expression using the {variable:[a-z-]+} syntax.
     *
     * @param string $route The route URL.
     * @param array $params Parameters (controller, action, etc.).
     * @return void
     */
    public function add(string $route, array $params = []): void
    {
        $route = str_replace('/', '\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = "/^$route\/?$/i";

        $this->routes[$route] = $params;
    }

    /**
     * Match a route path to the routes in the routing table.
     *
     * Setting the appropriate properties if a route is found.
     *
     * @param string $path The route path.
     * @return bool True if match found, false otherwise.
     */
    public function match(string $path): bool
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $path, $matches)) {

                $matches = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->parseParams(array_merge($matches, $params));
                return true;
            }
        }
        return false;
    }

    /**
     * Parse route parameters.
     *
     * Extract controller and action names from the array
     * of parameters and set them to the properties.
     *
     * @param array $params Route parameters.
     * @return void
     */
    protected function parseParams(array $params): void
    {
        [$this->controller, $this->action] = [$params['controller'] ?? '', $params['action'] ?? ''];

        unset($params['controller'], $params['action']);
        $this->params = $params;
    }

    /**
     * Dispatch the route.
     *
     * Creating the controller object and running the action method.
     *
     * @param string $url The request URL.
     * @return void
     *
     * @throws \Core\Exception\HttpException
     */
    public function dispatch(string $url): void
    {
        $route = $this->getRoutePath($url);

        if ($this->match($route)) {
            $toPascalCase = function (string $string): string {
                return str_replace('-', '', ucwords($string, '-'));
            };
            $controllerName = $toPascalCase($this->controller);
            $controllerName = $this->addNamespace($controllerName);

            if (class_exists($controllerName)) {
                $controller = new $controllerName($this->params);
                $action = lcfirst($toPascalCase($this->action));

                $suffix = AbstractController::ACTION_NAME_SUFFIX;
                if (!preg_match("/$suffix$/i", $action)) {
                    $controller->$action();
                } else {
                    throw new HttpException(404, "Method $action (in controller $controllerName) not found.");
                }
            } else {
                throw new HttpException(404, "Controller class $controllerName not found.");
            }
        } else {
            throw new HttpException(404, 'No route matched.');
        }
    }

    /**
     * Get the route path from the request URL.
     *
     * @param string $url The request URL.
     * @return string The route path.
     */
    protected function getRoutePath(string $url): string
    {
        return parse_url($url, PHP_URL_PATH) ?: '/';
    }

    /**
     * Add controllers namespace to relative class name.
     *
     * @param string $class Relative class name.
     * @return string Class name prefixed with controllers namespace.
     */
    protected function addNamespace(string $class): string
    {
        if ($this->namespace !== '' && strpos($class, '\\') !== 0) {
            return $this->namespace . '\\' . $class;
        }
        return $class;
    }
}
