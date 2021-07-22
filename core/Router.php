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
        $this->parseUrl($url);

        if (!$this->match($this->path)) {
            throw new HttpException(404, 'No route matched.');
        }

        $controller = $this->createController($this->controller);
        $controller->setParameters($this->params);
        $controller->callAction($this->action);
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
                $this->parseParams(array_merge($matches, $params));
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
