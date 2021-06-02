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
}
