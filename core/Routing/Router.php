<?php

namespace Core\Routing;

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
     * Array of routes (the routing table).
     *
     * @var array
     */
    protected $routes = [];

    /**
     * The route path from the request URL.
     *
     * @var string
     */
    protected $path = '';

    /**
     * Query parameters from the request URL.
     *
     * @var array
     */
    protected $query = [];

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
        $this->namespace = trim($namespace, '\\');
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
     * Route parameters must contain the controller and action
     * under the appropriate keys. Values in the parameters array take
     * precedence over the values retrieved from the route string.
     * @see \Core\RouteParameters::CONTROLLER
     * @see \Core\RouteParameters::ACTION
     *
     * @param string $route The route path.
     * @param array $params Route parameters.
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
        $this->parseUrl($url);

        if (!$this->match($this->path)) {
            throw new HttpException(404, 'No route matched.');
        }

        $controller = $this->createController($this->params[RouteParameters::CONTROLLER]);
        $controller->setParameters($this->params, $this->query);
        $controller->callAction($this->params[RouteParameters::ACTION]);
    }

    /**
     * Parse request URL.
     *
     * Extract the URL path and an array of URL query
     * parameters and set them to the properties.
     *
     * @param string $url The request URL.
     * @return void
     */
    protected function parseUrl(string $url): void
    {
        $components = parse_url($url);
        $this->path = $components['path'] ?: '/';

        if (isset($components['query'])) {
            parse_str(html_entity_decode($components['query']), $this->query);
        }
    }

    /**
     * Match a route path to the routes in the routing table.
     *
     * Setting the appropriate properties if a route is found.
     *
     * @param string $path The route path.
     * @return bool True if match found, false otherwise.
     */
    protected function match(string $path): bool
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $path, $matches)) {
                $matches = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->params = RouteParameters::prepare(array_merge($matches, $params), $this->namespace);
                return true;
            }
        }
        return false;
    }

    /**
     * Create an instance of the controller class.
     *
     * @param string $class Controller class name.
     * @return \Core\ControllerInterface Controller instance.
     *
     * @throws \Core\Exception\HttpException
     */
    protected function createController(string $class): ControllerInterface
    {
        if (!class_exists($class)) {
            throw new HttpException(404, "Controller class '$class' not found.");
        }
        return new $class();
    }
}
