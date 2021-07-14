<?php

namespace Core;

use Core\Exception\HttpException;

class Router
{
    /**
     * Default controller namespace.
     *
     * Must contain a trailing slash.
     *
     * @var string
     */
    protected const DEFAULT_CONTROLLER_NS = 'App\Controllers\\';

    /**
     * Controllers namespace.
     *
     * Relative controller names will be prefixed with this namespace.
     *
     * @var string
     */
    protected $namespace = '';

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
     * Get all the routes from the routing table.
     *
     * @return array Array of routes.
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Match a URL to the routes in the routing table.
     *
     * Setting the parameters property if a route is found.
     *
     * @param string $url The route URL.
     * @return bool True if match found, false otherwise.
     */
    public function match(string $url): bool
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {

                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Dispatch the route.
     *
     * Creating the controller object and running the action method.
     *
     * @param string $url The route URL.
     * @return void
     *
     * @throws \Core\Exception\HttpException
     */
    public function dispatch(string $url): void
    {
        $url = $this->removeQueryStringVars($url);

        if ($this->match($url)) {
            $toPascalCase = function (string $string): string {
                return str_replace('-', '', ucwords($string, '-'));
            };
            $controllerName = $toPascalCase($this->params['controller']);
            $controllerName = $this->addNamespace($controllerName);

            if (class_exists($controllerName)) {
                $controller = new $controllerName($this->params);
                $action = lcfirst($toPascalCase($this->params['action']));

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
     * Remove the query string variables from the URL.
     *
     * As the full query string is used for the route, any
     * variables at the end will need to be removed before
     * the route is matched to the routing table.
     *
     * @param string $url The route URL.
     * @return string The URL with the query string variables removed.
     */
    protected function removeQueryStringVars(string $url): string
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
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

    /**
     * Get the full namespace for a controller class.
     *
     * The sub-namespace defined in the route parameters
     * is added to default namespace.
     *
     * @return string Full controller namespace.
     */
    protected function getNamespace(): string
    {
        $namespace = static::DEFAULT_CONTROLLER_NS;
        if (isset($this->params['namespace'])) {
            $subNamespace = ucwords($this->params['namespace'], '\\');
            $namespace .= "$subNamespace\\";
        }
        return $namespace;
    }

    /**
     * Get the currently matched parameters.
     *
     * @return array Parameters from the matched route.
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
