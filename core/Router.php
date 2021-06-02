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
     * @param string $route The route URL.
     * @param array $params Parameters (controller, action, etc.).
     * @return void
     */
    public function add(string $route, array $params)
    {
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
     * Match a URL to the routes in the routing table,
     * setting the parameters property if a route is found.
     *
     * @param string $url The route URL.
     * @return bool True if match found, false otherwise.
     */
    public function match(string $url): bool
    {
        if (array_key_exists($url, $this->routes)) {
            $this->params = $this->routes[$url];
            return true;
        }
        return false;
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
