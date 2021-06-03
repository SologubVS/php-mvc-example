<?php

class Router
{
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
    public function add(string $route, array $params = [])
    {
        $route = str_replace('/', '\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = "/^$route$/i";

        $this->routes[$route] = $params;
    }

    /**
     * Get all the routes from the routing table.
     *
     * @return array
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
     */
    public function dispatch(string $url)
    {
        if ($this->match($url)) {
            $toPascalCase = function (string $string): string {
                return str_replace('-', '', ucwords($string, '-'));
            };

            $controllerName = $toPascalCase($this->params['controller']);
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                $action = lcfirst($toPascalCase($this->params['action']));
                if (is_callable([$controller, $action])) {
                    $controller->$action();
                } else {
                    echo "Method $action (in controller $controllerName) not found.";
                }
            } else {
                echo "Controller class $controllerName not found.";
            }
        } else {
            echo "No route matched.";
        }
    }

    /**
     * Get the currently matched parameters.
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
