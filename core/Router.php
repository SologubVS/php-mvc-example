<?php

namespace Core;

use Core\Exception\HttpException;

class Router
{
    /**
     * Controller parameter name.
     *
     * Used to extract the controller from route parameters.
     *
     * @var string
     */
    public const CONTROLLER = 'controller';

    /**
     * Controller action parameter name.
     *
     * Used to extract controller action from route parameters.
     *
     * @var string
     */
    public const ACTION = 'action';

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
     * Route parameters must contain the controller and action
     * under the appropriate keys. Values in the parameters array take
     * precedence over the values retrieved from the route string.
     * @see \Core\Router::CONTROLLER
     * @see \Core\Router::ACTION
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
        $route = $this->getRoutePath($url);

        if ($this->match($route)) {
            if (class_exists($this->controller)) {
                $controller = new $this->{'controller'}($this->params);
                $controller->callAction($this->action);
            } else {
                throw new HttpException(404, "Controller class '{$this->controller}' not found.");
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
        $this->prepareMethod($params[static::CONTROLLER] ?? '', $params[static::ACTION] ?? '');
        unset($params[static::CONTROLLER], $params[static::ACTION]);
        $this->params = $params;
    }

    /**
     * Prepare controller name and action name.
     *
     * Set the prepared controller name and action
     * name to the appropriate properties.
     *
     * @param string $controller Controller name.
     * @param string $action Controller action name.
     * @return void
     */
    protected function prepareMethod(string $controller, string $action): void
    {
        foreach ([&$controller, &$action] as &$string) {
            if (strpos($string, '-') !== false) {
                $string = str_replace('-', '', ucwords($string, '-'));
            }
        }
        if ($controller !== '') {
            $controller = $this->addNamespace(ucfirst($controller));
        }
        [$this->controller, $this->action] = [ltrim($controller, '\\'), lcfirst($action)];
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
