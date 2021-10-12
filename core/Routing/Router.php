<?php

namespace Core\Routing;

use Core\Exception\HttpException;

class Router
{
    /**
     * The base path of the route.
     *
     * Route paths will be prefixed with this path.
     *
     * @var string
     */
    protected $base = '';

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
     * Matched route.
     *
     * @var \Core\Routing\RouteInterface
     */
    protected $route;

    /**
     * Create a new router.
     *
     * @param string $base The base path of the route.
     * @param string $namespace Controllers namespace.
     */
    public function __construct(string $base = '/', string $namespace = '')
    {
        $this->base = $base;
        $this->namespace = $namespace;
    }

    /**
     * Add a route to the routing table.
     *
     * Before being added to the routing table,
     * the route string is prefixed with the base path,
     * then the route string is converted to a regular expression
     * and can contain variables.
     *
     * Route parameters must contain the controller and action
     * under the appropriate keys. Values in the parameters array take
     * precedence over the values retrieved from the route string.
     *
     * @see \Core\Routing\Router::convertRouteToRegex()
     * @see \Core\Routing\RouteParameters::CONTROLLER
     * @see \Core\Routing\RouteParameters::ACTION
     *
     * @param string $route The route path.
     * @param array $params Route parameters.
     * @return void
     */
    public function add(string $route, array $params = []): void
    {
        $route = $this->convertRouteToRegex(
            $this->addBasePath($this->base, $route)
        );
        $this->routes[$route] = $params;
    }

    /**
     * Convert route string to regular expression.
     *
     * The route string can contain variables in the {variable} format.
     *
     * By default, the regular expression [a-z-\d]+ is used to retrieve
     * the value of a variable. It is possible to specify a different
     * regular expression using the {variable:[a-z-\d]+} syntax.
     *
     * @param string $route The route path.
     * @return string Route string as regular expression.
     */
    protected function convertRouteToRegex(string $route): string
    {
        $route = str_replace('/', '\/', rtrim($route, '/') . '/?');

        if (strpos($route, '{') !== false) {
            $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-\d]+)', $route);
            $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        }
        return "/^{$route}$/i";
    }

    /**
     * Add the base path to the route path.
     *
     * @param string $base The base path.
     * @param string $route The route path.
     * @return string The full path of the route.
     */
    protected function addBasePath(string $base, string $route): string
    {
        return $route === '' ? $base : rtrim($base, '/') . '/' . ltrim($route, '/');
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
        if (!$this->match(parse_url($url, PHP_URL_PATH))) {
            throw new HttpException(404, 'No route matched.');
        }

        $controller = $this->createController($this->route->getController());
        $controller->setRouteParameters($this->route->getParams());
        $controller->setQueryParameters($this->getQueryParameters($url));
        $controller->callAction($this->route->getAction());
    }

    /**
     * Get query parameters from the request URL as an array.
     *
     * @param string $url The request URL.
     * @return array Query parameters.
     */
    public static function getQueryParameters(string $url): array
    {
        parse_str(html_entity_decode(parse_url($url, PHP_URL_QUERY)), $query);
        return $query;
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

                $params = new RouteParams(array_merge($matches, $params));
                $this->route = new Route($path, $params, $this->namespace);
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
